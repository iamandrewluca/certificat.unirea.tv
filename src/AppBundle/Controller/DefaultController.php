<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        return new Response("form");
    }

    /**
     * @Route("/{encryptedName}/", name="imageCertificate")
     * @Method("GET")
     */
    public function imageCertificateArticle($encryptedName)
    {
        $personName = base64_decode($encryptedName);

        return new Response("post for" . $personName);
    }

    /**
     * @Route("/{encryptedName}/image/", name="image")
     * @Method("GET")
     */
    public function imageCertificate($encryptedName)
    {
        $personName = base64_decode($encryptedName);

        return new Response("image for" . $personName);
    }


}
