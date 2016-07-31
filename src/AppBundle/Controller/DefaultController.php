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
     * @return Response
     * @internal param Request $request
     */
    public function indexAction()
    {
        return $this->render('pages/index.html.twig');
    }

    /**
     * @Route("/")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexActionPost(Request $request)
    {
        $personName = $request->get('name');

        // TODO: Name Validation
        if (strlen($personName) > 32) {
            return $this->redirectToRoute('homepage');
        }

        $encryptedName = base64_encode($personName);

        return $this->redirectToRoute('imageCertificate', array(
            'encryptedName' => $encryptedName
        ));
    }

    /**
     * @Route("/{encryptedName}/", name="imageCertificate")
     * @Method("GET")
     * @param $encryptedName
     * @return Response
     * @internal param Request $request
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
     * @param $encryptedName
     * @return Response
     */
    public function imageCertificate($encryptedName)
    {
        $personName = base64_decode($encryptedName);
        return $this->getImageResponse($personName);
    }

    /**
     * @Route("/{encryptedName}/download/", name="download")
     * @Method("GET")
     * @param $encryptedName
     * @return Response
     */
    public function imageDownload($encryptedName)
    {
        $personName = base64_decode($encryptedName);
        return $this->getImageResponse($personName, "full");
    }

    /**
     * @param string $personName
     * @param string $size
     * @return Response
     */
    private function getImageResponse($personName = "UNIREA.TV", $size = "normal")
    {
        $image = imagecreatefromjpeg('assets/images/certificat.jpg');

        $color = imagecolorallocate($image, 43, 59, 75);
        $font = 'assets/fonts/Lighthouse.ttf';
        $fontSize = 125;

        /**
         * Calculate size of text with this font (x,y,w,h)
         */

        $box = imagettfbbox($fontSize, 0, $font, $personName);
        $x = $box[0] + (imagesx($image) / 2) - ($box[4] / 2);
        $y = 770;

        /**
         * Write text to image
         */

        imagettftext($image, $fontSize, 0, $x, $y, $color, $font, $personName);

        /**
         * Resize image if needed
         */

        if ($size == "normal") {
            $newImage = imagescale($image, 600);
            imagedestroy($image);
        } else {
            $newImage = $image;
        }

        /**
         * Send image to response
         */

        $response = new Response();
        $response->headers->set('Content-Type', 'image/png');
        $response->sendHeaders();

        imagepng($newImage, null);
        imagedestroy($newImage);

        return $response;
    }
}
