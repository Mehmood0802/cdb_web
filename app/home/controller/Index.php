<?php 
declare(strict_types = 1);

namespace app\home\controller ;

use app\home\Controller ;
use think\facade\View ;
use think\facade\Db ;


use app\home\model\Site ;
use app\home\model\SiteArticle ;
use app\home\model\SiteFeedback ;


Class Index extends Controller{


    public function index(){

        View::assign('site', Site::getOne(1) ) ;

        View::assign('tabs', 'index' ) ;
        
        return view() ;
    }


    public function instruction(){

        View::assign('site', Site::getOne(1) ) ;

        View::assign('tabs', 'instruction' ) ;

        return view() ;
    }



    public function contact(){
        
        View::assign('site', Site::getOne(1) ) ;

        View::assign('tabs', 'contact' ) ;

        return view() ;
    }



    


    public function about(){

        View::assign('site', Site::getOne(1) ) ;

        View::assign('tabs', 'about' ) ;

        return view() ;
    }


    public function news(){
        
        View::assign('site', Site::getOne(1) ) ;

        $data = SiteArticle::getOneW( ['is_home'=>1,'status'=>1] );
        View::assign('data', $data ) ;


        $list = SiteArticle::getListPO(['cate_id'=>1,'status'=>1],'*','id desc',15) ;
        View::assign('list', $list ) ;
        View::assign('tabs', 'news' ) ;



        return view() ;
    }


    public function detail(){

        View::assign('site', Site::getOne(1) ) ;


        $data = SiteArticle::getOne(input('id')) ;
        View::assign('data', $data ) ;


        $list =  SiteArticle::getListPO(['cate_id'=>1,'status'=>1],'*','id desc',3) ;
        View::assign('list', $list ) ;


        View::assign('tabs', 'news' ) ;
        return view() ;
    }



    //反馈
    public function feedback(){
        if($this->isPost){
            return SiteFeedback::add($this->postData) ;
        }
    }




    public function sitemap_add(){

        $base_url = 'https://poweron.sg' ;

        // 假设这是你网站上的所有URL路径
        $urls = [
            $base_url.'/home/index/index.html',
            $base_url.'/home/index/instruction.html',
            $base_url.'/home/index/contact.html',
            $base_url.'/home/index/about.html',
            $base_url.'/home/index/news.html',
        ];


        $list = SiteArticle::getAll();

        if($list){
            foreach($list as $k => $v){
                $urls[] = $base_url.'//home/index/detail.html?id='. + $v['id'] ;
            }
        }

         
        $sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $sitemap .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
         
        foreach ($urls as $k => $v) {
            $sitemap .= "<url>\n";
            $sitemap .= "<loc>" . $v . "</loc>\n";
            $sitemap .= "<changefreq>daily</changefreq>\n";
            if($k == 0){
                $sitemap .= "<priority>1</priority>\n";
            }
            if($k > 0 && $k < 5){
                $sitemap .= "<priority>0.85</priority>\n";
            }
            if($k >= 5){
                $sitemap .= "<priority>0.65</priority>\n";
            }
            $sitemap .= "</url>\n";
        }
        $sitemap .= "</urlset>";


        $path = $_SERVER['DOCUMENT_ROOT'].'/sitemap.xml' ;
         
        file_put_contents($path, $sitemap) ;

        echo '更新成功！' ;



    }


    public function sitemap_add2(){

        $base_url = 'https://poweron.com.sg' ;

        // 假设这是你网站上的所有URL路径
        $urls = [
            $base_url.'/home/index/index.html',
            $base_url.'/home/index/instruction.html',
            $base_url.'/home/index/contact.html',
            $base_url.'/home/index/about.html',
            $base_url.'/home/index/news.html',
        ];


        $list = SiteArticle::getAll();

        if($list){
            foreach($list as $k => $v){
                $urls[] = $base_url.'//home/index/detail.html?id='. + $v['id'] ;
            }
        }

         
        $sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $sitemap .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
         
        foreach ($urls as $k => $v) {
            $sitemap .= "<url>\n";
            $sitemap .= "<loc>" . $v . "</loc>\n";
            $sitemap .= "<changefreq>daily</changefreq>\n";
            if($k == 0){
                $sitemap .= "<priority>1</priority>\n";
            }
            if($k > 0 && $k < 5){
                $sitemap .= "<priority>0.85</priority>\n";
            }
            if($k >= 5){
                $sitemap .= "<priority>0.65</priority>\n";
            }
            $sitemap .= "</url>\n";
        }
        $sitemap .= "</urlset>";


        $path = $_SERVER['DOCUMENT_ROOT'].'/sitemap2.xml' ;
         
        file_put_contents($path, $sitemap) ;

        echo '更新成功！' ;



    }



}










