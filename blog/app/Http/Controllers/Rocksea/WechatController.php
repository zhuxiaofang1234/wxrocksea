<?php
/**
 * Created by PhpStorm.
 * User: zxf
 * Date: 2016/8/18
 * Time: 11:14
 */
namespace App\Http\Controllers\Rocksea;
use App\Http\Controllers\Controller;
use EasyWeChat\Foundation\Application;

use Log;
use EasyWeChat\Message\Text;
use EasyWeChat\Message\Image;
use EasyWeChat\Core\AccessToken;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Article;


class WechatController extends Controller{

        /**
         * 处理微信的请求消息
         *服务器连接
         * @return string
         */
    public function serve()
    {
        //基本配置
        $options = config('wechat');
        $app = new Application($options);
        // 从项目实例中得到服务端应用实例。
        $server = $app->server;
        $server->setMessageHandler(function ($message) {
           $fromUser=  $message->FromUserName ;
           $msgType = $message->MsgType;
            switch($msgType){
                case 'event':
                    if($message->Event=='subscribe'){
                        $rs = '欢迎关注武汉岩海微信公众账号！';
                    };
                break;
                case 'text':
                    $rs = $this->replyText();
                    break;
                case 'image':
                   $mid = $message->MediaId;
                    $MsgId=$message->MsgId;
                    log::info($message);
                    $this->reciveImg($mid,$MsgId);
                    $rs =$this->replyImg();

                    break;
                default :
                    $rs = '有啥可以帮助你的！';
                    break;
            }
           return $rs;
        });
        $response = $server->serve();
        return $response;

    }

    //获取access token
    public function getAccessToken(){

        $app_id = config('wechat.app_id');
        $app_secret=config('wechat.secret');
        $accessToken = new AccessToken($app_id, $app_secret);
        $token = $accessToken->getToken(); // token 字符串
        return $token;

    }

    //回复文本消息
    public function replyText(){

        $text = new Text(['content' => '您好！武汉岩海！']);
        return $text ;
    }

    //上传临时素材
    public function material(){
        $options = config('wechat');
        $app = new Application($options);
        $temporary = $app->material_temporary;
        $result = $temporary->uploadImage('E:/01.jpg');
    }

    //下载用户发过来的图片消息
    public function reciveImg($mediaId,$MsgId){
        $options = config('wechat');
        $app = new Application($options);
        $temporary = $app->material_temporary;

        $temporary->download($mediaId, "E:/", $MsgId.".jpg");
    }



    //回复图片消息
    public function replyImg(){

        $mediaId="r9_33jmoYkeOUrM-QRO0glgOHt94xJnor6R4VZHfc8oIwLOjt6lF3UymTfWRpbNl";
        $img = new Image(['media_id' => $mediaId]);
        return $img;
    }

    //回复单图文消息
    public function replyNews(){

        $data = $this->getNews();
        $title  =$data['news_item'][0]['title'];
        $description=$data['news_item'][0]['digest'];
        $url="http://mp.weixin.qq.com/s?__biz=MzIwMzU1MzkwOQ==&mid=100000004&idx=1&sn=d32ea9edcd1417b60485543ee28caae9#rd";
        $image="http://mmbiz.qpic.cn/mmbiz_jpg/apbSYdiaUwbUicjUqo8qhTNOMqeJic2iaTnfsWrzfewpMyN1ictVuQfm09bNHgGjxQubcfJyNRuymib7H6qBDlDRoJ7w/0?wx_fmt=jpeg";
        $news = new News([
            'title'       =>$title,
            'description' => $description,
            'url'         =>$url,
            'image'       => $image,
        ]);
        return $news;

    }


    //获取图文永久素材
    public function getNews(){
        $options = config('wechat');
        $app = new Application($options);
        $material = $app->material;
        $mediaId='kxK93GhcTomJK_4KJf3LREK52IIOm4hnYN7WUbI4pBY';
        $resource = $material->get($mediaId);
      return $resource;
    }


    //上传永久图片
    public function addForeverImg(){
        $options = config('wechat');
        $app = new Application($options);
        $material = $app->material;
        $result =  $material->uploadImage('E:/02.jpg'); //print_r($result['media_id'])
       return $result;
    }

    //上传永久文章内容图片
    public function addContentImg(){
        $options = config('wechat');
        $app = new Application($options);
        $material = $app->material;
        $result = $material->uploadArticleImage('E:/04.jpg');
        print_r($result);exit;
    }


    //上传永久单图文消息
    public function addNews(){
        $options = config('wechat');
        $app = new Application($options);
        $material = $app->material;
        $article = new Article([
            'title'=>'可爱的小熊',
            'thumb_media_id'=>'kxK93GhcTomJK_4KJf3LRFukd4VPOgVI6OKtvBXsavY',
            'author'=>'朱小芳',
            'digest'=>'今天天气好晴朗!',
            'content'=>'噢噢噢噢啊啊啊啊啊',
        ]);
        $material->uploadArticle($article);

    }







}