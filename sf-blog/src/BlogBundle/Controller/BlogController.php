<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Date;

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
        $latestPosts = $this->getDoctrine()
            ->getRepository('BlogBundle:Post')
            ->findBy([], ['publishedAt' => 'DESC'], 3);

        return $this->render('BlogBundle:Blog:index.html.twig', [
            'posts' => $latestPosts,
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

        if ($category == null)
        {
            $this->createNotFoundException("Category not found");
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
            $this->createNotFoundException("Post not found");
        }
        $comments = $commentRepository->findBy(['post' => $post->getId(), 'validated' => true]);


        $comment = new Comment();
        $comment->setPost($post);

        $form = $this->createFormBuilder($comment)
            ->add('message', TextType::class)
            ->add('pseudo', TextType::class)
            ->add('submit', SubmitType::class, array('label' => 'Create Post'))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($comment);
            $manager->flush();

            return $this->render("BlogBundle:Blog:post.html.twig", ['post' => $post, 'comments' => $comments, 'form' => $form->createView()]);
        }

        return $this->render("BlogBundle:Blog:post.html.twig", ['post' => $post, 'comments' => $comments, 'form' => $form->createView()]);
    }
}
