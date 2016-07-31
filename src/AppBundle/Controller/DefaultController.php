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
        return $this->render('pages/index.html.twig');
    }

    /**
     * @Route("/")
     * @Method("POST")
     */
    public function indexActionPost(Request $request)
    {
        $personName = $request->get('name');
        $encryptedName = base64_encode($personName);

        return $this->render('pages/thanks.html.twig', array(
            'personName' => $personName,
            'encryptedName' => $encryptedName
        ));
    }

    /**
     * @Route("/{encryptedName}/", name="imageCertificate")
     * @Method("GET")
     */
    public function imageCertificateArticle($encryptedName)
    {
        $personName = base64_decode($encryptedName);

        return $this->render('pages/image.html.twig', array(
            'personName' => $personName,
            'encryptedName' => $encryptedName
        ));
    }

    /**
     * @Route("/{encryptedName}/image/", name="image")
     * @Method("GET")
     */
    public function imageCertificate($encryptedName)
    {
        // TODO: Name Validation

        $personName = base64_decode($encryptedName);

        $image = imagecreatefromjpeg('assets/images/certificate.jpg');
        $color = imagecolorallocate($image, 255, 0, 0);
        $font = 'assets/fonts/ShadedLarch.ttf';

        $size = 100;
        $x = 300;
        $y = 300;

        /**
         * Write text to image
         */

        imagettftext($image, $size, 0, $x, $y, $color, $font, $personName);

        /**
         * Send image to response
         */

        $response = new Response();
        $response->headers->set('Content-Type', 'image/png');
        $response->sendHeaders();

        imagepng($image, null);
        imagedestroy($image);

        return $response;
    }


}
