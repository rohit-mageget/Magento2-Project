<?php
namespace Mageget\Checkout\Controller\Index;

use Magento\Framework\Json\Helper\Data as JsonHelper;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
	protected $connection;
    protected $_mediaDirectory;
    protected $_fileUploaderFactory;
    public $_storeManager;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\ResourceConnection $connection,
        JsonHelper $jsonHelper,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Framework\View\Result\PageFactory $pageFactory)
	{
		$this->_pageFactory = $pageFactory;
        $this->connection = $connection;      
        $this->jsonHelper = $jsonHelper;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_storeManager = $storeManager;
		return parent::__construct($context);
	}

	public function execute(){

        
        
        // $product = $this->getRequest()->getPostValue();
        $image = $this->getRequest()->getFiles();
        // $json  = json_encode($product);
        // $array = json_decode($json, true);
    //    $array = json_encode(array_merge(json_decode($product, true),json_decode($image, true)));

        // echo "<pre>";
        // print_r($product);
        // die("controller");
        
        $message = "";
        $newFileName = "";
        $error = false;
        $data = array();
        
        try{
            $target = $this->_mediaDirectory->getAbsolutePath('wallpaper/');        
            
            //attachment is the input file name posted from your form
            $uploader = $this->_fileUploaderFactory->create(['fileId' => 'image']);
            
            $_fileType = $uploader->getFileExtension();
            $uniqid = uniqid();
            $newFileName = $uniqid .'.'. $_fileType;
            
            /** Allowed extension types */
            $uploader->setAllowedExtensions(['jpg', 'jpeg', 'png']);
            /** rename file name if already exists */
            $uploader->setAllowRenameFiles(true);
            
            $result = $uploader->save($target, $newFileName); //Use this if you want to change your file name
            //$result = $uploader->save($target);
            if ($result['file']) {
                
                $_mediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
                
                $_src = $_mediaUrl.'wallpaper/'.$newFileName;
                
                $error = false;
                $message = __("File has been successfully uploaded");
                
                $html = '<div class="image item base-image" data-role="image" id="'. $uniqid .'">
                            <div class="wallpaper-image-wrapper">
                                <div class="radioContainer">
                                    <input data-src="'.$_src.'" data-srctype="'. $_fileType .'" data-srcfilename="'. $newFileName .'" checked="checked" class="selectImage selectImage-'. $uniqid .'" type="radio" name="selectImage" value="'. $newFileName .'" />
                                </div>
                                <div class="imageContainer">
                                    <img class="wallpaper-image" data-role="image-element" src="'.$_src.'" alt="">
                                </div>
                                <div class="delete-action">
                                    <button type="button" class="action-remove" data-role="delete-button" data-image="'.$newFileName.'" title="'. __('Delete image') .'"><span>'. __('Delete image') .'</span></button>
                                </div>
                            </div>
                        </div>';
                
                $data = array('filename' => $newFileName, 'path' => $_mediaUrl.'wallpaper/'.$newFileName, 'fileType' => $_fileType, 'html' => $html);
            }
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
        }
        
        $resultJson = $this->resultJsonFactory->create();

        return $resultJson->setData([
                    'message' => $message,
                    'data' => $data,
                    'error' => $error
        ]);
    }
}