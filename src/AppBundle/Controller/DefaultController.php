<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/unirea-certificat")
 */
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

        // TODO: Name Validation
        if (strlen($personName) > 32) {
            return $this->redirectToRoute('homepage');
        }

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
        $personName = base64_decode($encryptedName);

        $image = imagecreatefromjpeg('assets/images/certificat.jpg');
        $color = imagecolorallocate($image, 43, 59, 75);
        $font = 'assets/fonts/Lighthouse.ttf';

        $size = 125;

        $box = imagettfbbox($size, 0, $font, $personName);

        $x = $box[0] + (imagesx($image) / 2) - ($box[4] / 2);
        $y = 770;

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
