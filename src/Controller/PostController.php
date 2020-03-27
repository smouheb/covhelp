<?php


namespace App\Controller;


use App\Entity\Post;
use App\Form\CommentPostType;
use App\Form\PostType;
use App\Repository\PostRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return Response
     * @Route(path="/create-post", name="post")
     */
    public function createPost(Request $request)
    {
        $form = $this->createForm(PostType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            try {

                $data = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($data);
                $em->flush();

                $this->addFlash('success', 'Thanks'.$data->getAuthor().' your message has been posted');
                return $this->redirectToRoute('listOfPost');

            } catch (\Exception $exception){

                 $this->addFlash('error', 'Something went wrong... try again..');
                 $this->logger->error('Issue in Post controller '.$exception->getMessage());
                 return $this->redirectToRoute('listOfPost');
            }
        }
        return $this->render('forum/post.html.twig', [

            'form' => $form->createView()
        ]);
    }

    /**
     * @return Response
     * @Route(path="/blog-post", name="listOfPost")
     */
    public function listOfPost()
    {
        $post = $this->getDoctrine()
                     ->getRepository(Post::class)
                     ->findBy([], ['postDate' => 'DESC']);

        return $this->render('forum/listOfPosts.html.twig', [
            'post' => $post
        ]);
    }

    /**
     * @return Response
     * @Route(path="/post-detail/{id}", name="detailPost")
     */
    public function detailPost($id, Request $request)
    {
        $form = $this->createForm(CommentPostType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $idPost = $request->attributes->get('id');
            $postObject = $this->getDoctrine()->getRepository(Post::class)->find($idPost);

            try {

                $data = $form->getData();
                $data->setPost($postObject);

                $em = $this->getDoctrine()->getManager();
                $em->persist($data);
                $em->flush();

                $this->addFlash('success', 'Thanks'.$data->getPseudo().' your message has been posted');
                return $this->redirectToRoute('detailPost', ['id' => $idPost]);

            } catch (\Exception $exception){

                $this->addFlash('error', 'Something went wrong... try again..');
                $this->logger->error('Issue in Post controller '.$exception->getMessage());
                return $this->redirectToRoute('detailPost', ['id' => $idPost]);
            }
        }

        $detailPost = $this->getDoctrine()
                    ->getRepository(Post::class)
                    ->detailPost($id);

        return $this->render('forum/detailPost.html.twig', [
            'detailPost' => $detailPost,
            'form' => $form->createView()
        ]);
    }

}