<?php


namespace App\Controller;


use App\Service\NumericToRomanConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController
{

    /**
     * @Route(  "/home/{nameOfPerson}",
     *          name="home_index",
     *          defaults={"nameOfPerson"="toto"}
     * )
     * @param Request $request
     * @param $name
     * @return Response
     */
    public function indexAction(Request $request, $nameOfPerson){
        $age = $request->get("age") ?? 10;

        $fruits = [
            "pommes", "poires", "oranges", "grenades"
        ];

        return $this->render("home/home.html.twig", [
            "name"=>$nameOfPerson,
            "age"=>$age,
            "fruitList"=>$fruits ]
        );
    }

    /**
     * @Route("/add/{n1}/{n2}", name="home_add", requirements={"n1"="\d+", "n2"="\d+"})
     * @param $n1
     * @param $n2
     */
    public function addAction($n1, $n2){
        return $this->render("home/add.html.twig",
            ["n1"=>$n1, "n2"=>$n2, "sum"=>($n1+$n2)]
        );
    }

    /**
     * @Route("/login-admin", name="admin_login")
     */
    public function AdminLoginAction(AuthenticationUtils $security){
       return $this->render("home/login.html.twig", [
           "userName"=> $security->getLastUsername(),
           "error"=> $security->getLastAuthenticationError()
       ]) ;
    }

    /**
     * @Route("/")
     */
    public function testAction(NumericToRomanConverter $converter){
        return $this->render("test.html.twig");
    }

}