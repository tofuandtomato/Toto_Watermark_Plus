<?php

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @package    Toto_Watermarkplus
 * @author     Tofu & Tomato E-commerce (http://tofuandtomato.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Toto_Watermarkplus_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        // load the watermark and the photo
        $watermarkPath = Mage::getBaseDir('media') . DS . 'watermarkplus' . DS .  Mage::getStoreConfig("watermarkplus_options/settings/image");
        $watermark = imagecreatefrompng($watermarkPath);

        // decrypt image path
        $imagePath = $_GET['image'];
        $imagePath = urldecode($imagePath);
        $imagePath = str_replace('plus', '+', $imagePath);
        $imagePath = base64_decode(Mage::helper('core')->decrypt($imagePath));

        $imagePath = Mage::getBaseDir() . $imagePath;
        $image = imagecreatefromjpeg($imagePath);

        if(!$image) {
            $image = imagecreatefrompng($imagePath);
        }

        // center watermark on the photo
        $wx = imagesx($image)/2 - imagesx($watermark)/2;
        $wy = imagesy($image)/2 - imagesy($watermark)/2;

        if($wx < 0 || $wy < 0) {
            imagecopyresized($image, $watermark, 0, 0, 0, 0, imagesx($image), imagesy($image), imagesx($watermark), imagesy($watermark));
        } else {
            imagecopy($image, $watermark, $wx, $wy, 0, 0, imagesx($watermark), imagesy($watermark));
        }

        // output image data
        imagejpeg($image, NULL, 100);

        // release memory
        imagedestroy($image);
        imagedestroy($watermark);
    }
}