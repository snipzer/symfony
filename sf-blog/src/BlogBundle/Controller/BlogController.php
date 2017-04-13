<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Comment;
use Doctrine\DBAL\Driver\PDOException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BlogController
 * @package BlogBundle\Controller
 */
class BlogController extends Controller
{
    /**
     * Blog index action.
     *
     * @return Response
     */
    public function indexAction()
    {

        $speaker = $this->get('Blog.Speaker');

        $name = $speaker->sayMyName();

        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('loic.arif@gmail.com')
            ->setTo('loic.arif@gmail.com')
            ->setBody('Test');

        $sent = $this->get('mailer')->send($message);

        $latestPosts = $this->getDoctrine()
            ->getRepository('BlogBundle:Post')
            ->findBy([], ['publishedAt' => 'DESC'], 3);

        return $this->render('BlogBundle:Blog:index.html.twig', [
            'posts' => $latestPosts,
            'name' => $name,
        ]);
    }

    /**
     * Category detail action.
     *
     * @param string $categorySlug
     *
     * @return Response
     */
    public function categoryAction(Request $request)
    {
        $categoryRepository = $this->getDoctrine()->getRepository("BlogBundle:Category");
        $listCategory = $this->getDoctrine()->getRepository("BlogBundle:Post");

        $category = $categoryRepository->findOneBy(['slug' => $request->attributes->get('categorySlug')]);

        var_dump($category);
        if ($category == null)
        {
            throw $this->createNotFoundException("Category not found");
        }

        $lists = $listCategory->findBy(["category" => $category->getId()]);


        return $this->render("BlogBundle:Blog:category.html.twig", ["category" => $category, "lists" => $lists]);
    }

    /**
     * Post detail action.
     *
     * @param string $categorySlug
     * @param string $postSlug
     *
     * @return Response
     */
    public function postAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $postRepository = $this->getDoctrine()->getRepository("BlogBundle:Post");
        $commentRepository = $this->getDoctrine()->getRepository("BlogBundle:Comment");

        $post = $postRepository->findOneBy(['slug' => $request->attributes->get('postSlug')]);

        if ($post == null)
        {
            throw $this->createNotFoundException("Post not found");
        }
        $comments = $commentRepository->findBy(['post' => $post->getId(), 'validated' => true]);


        $comment = new Comment();
        $comment->setPost($post);

        $form = $this->createFormBuilder($comment)
            ->add('pseudo', TextType::class)
            ->add('message', TextType::class)
            ->add('submit', SubmitType::class, array('label' => 'Create Post'))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            try
            {
                $manager->persist($comment);
                $manager->flush();
                $this->addFlash('notice', 'Commented posted and his in waiting for validation by an administrator');
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }

            return $this->render("BlogBundle:Blog:post.html.twig", ['post' => $post, 'comments' => $comments, 'form' => $form->createView()]);
        }

        return $this->render("BlogBundle:Blog:post.html.twig", ['post' => $post, 'comments' => $comments, 'form' => $form->createView()]);
    }

    public function adminAction(Request $request)
    {
        /**
         * TODO: If user is not admin gtfo
         */

        $commentRepository = $this->getDoctrine()->getRepository("BlogBundle:Comment");

        $comments = $commentRepository->findBy(['validated' => false]);

        return $this->render("BlogBundle:Blog:admin.html.twig", ['comments' => $comments]);
    }

    public function validateAction(Request $request)
    {
        /**
         * TODO: If user is not admin gtfo
         */

        $manager = $this->getDoctrine()->getManager();
        $blog = $this->getDoctrine()->getRepository("BlogBundle:Comment")->findOneBy(['id' => $request->attributes->get("slug")]);

        $blog->setValidated(true);
        $manager->persist($blog);
        $manager->flush();

        $this->addFlash('validate', 'Comment authorize');

        $url = $this->generateUrl("blog_admin");

        return $this->redirect($url);
    }
}
