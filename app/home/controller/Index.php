<?php 
declare(strict_types = 1);

namespace app\home\controller ;
use app\home\Controller ;
use think\facade\View ;
use think\facade\Db ;
use think\facade\Request;
use app\home\model\Site ;
use app\home\model\SiteArticle ;
use app\home\model\SiteFeedback ;

Class Index extends Controller{

    function handleRedirection($url)
    {
        $parsedUrl = parse_url($url);
        $path = $parsedUrl['path'] ?? '';
        $query = $parsedUrl['query'] ?? '';
        if (preg_match('/[A-Z]/', $path)) {
            $path = strtolower($path);
        }
        if (!preg_match('/\.html$/i', $path)) {
            $path .= '.html';
        }
        $newUrl = $path;
        if ($query) {
            $newUrl .= '?' . $query;
        }
        if ($newUrl !== $url) {
            return redirect($newUrl, 301);
        }
        return null;
    }    
    
    public function index(){
        View::assign('site', Site::getOne(1) ) ;
        View::assign('tabs', 'index' ) ;
        View::assign('title', "Mobile Portable Charging Station Rental | Shared PowerBank | PowerOn SG");
        View::assign('description', "PowrOn SG offers portable mobile phone charger rental, charging stations & portable shared power bank rentals for events or daily use. Stay powered & Rent now!");
        View::assign('canonical',  Request::domain());
        return view() ;
    }


    public function instruction(){
        if ($redirect = $this->handleRedirection(Request::url())) {
            return $redirect;
        }
        View::assign('site', Site::getOne(1) ) ;
        View::assign('tabs', 'instruction' ) ;
        View::assign('title', "Mobile Power Supply Leasing Process | PowerOn SG");
        View::assign('description', "Learn how to lease mobile power supplies with PowerOn SG. Easy steps for renting, using, and returning power banks to stay charged on the go.");
        View::assign('canonical',  Request::url(true));
        return view() ;
    }



    public function contact(){
        if ($redirect = $this->handleRedirection(Request::url())) {
            return $redirect;
        }
        View::assign('site', Site::getOne(1) ) ;
        View::assign('tabs', 'contact' ) ;
        View::assign('title', "Contact PowerOn SG | Mobile Charging Station Rentals");
        View::assign('description', "Get in touch with PowerOn SG for mobile phone charging station rentals in Singapore: fast service, and reliable solutions. Contact us today!");
        View::assign('canonical',  Request::url(true));
        return view() ;
    }

    public function products(){
        if ($redirect = $this->handleRedirection(Request::url())) {
            return $redirect;
        }
        View::assign('site', Site::getOne(1) ) ;
        View::assign('tabs', 'products' ) ;
        View::assign('title', "Our Products | PowerOn SG");
        View::assign('description', "Get in touch with PowerOn SG for mobile phone charging station rentals in Singapore: fast service, and reliable solutions. Contact us today!");
        View::assign('canonical',  Request::url(true));
        return view() ;
    }
    public function about(){
        if ($redirect = $this->handleRedirection(Request::url())) {
            return $redirect;
        }
        View::assign('site', Site::getOne(1) ) ;
        View::assign('tabs', 'about' ) ;
        View::assign('title', "About Us - PowerOn SG: Leading Charging Solutions");
        View::assign('description', "PowerOn SG provides convenient mobile phone charging solutions with cutting-edge technology and reliable service. Stay powered up with our stations!");
        View::assign('canonical',  Request::url(true));
        return view() ;
    }

    public function notfound()
    {
        View::assign('site', Site::getOne(1) ) ;
        View::assign('tabs', 'notfound' ) ;
        View::assign('title', "404");
        View::assign('description', "404");
        return view() ;
    }


    public function thanks(){
        if ($redirect = $this->handleRedirection(Request::url())) {
            return $redirect;
        }
        View::assign('site', Site::getOne(1) ) ;
        View::assign('tabs', 'thanks' ) ;
        View::assign('title', "Thank You - PowerOn SG: Leading Charging Solutions");
        View::assign('description', "PowerOn SG provides convenient mobile phone charging solutions with cutting-edge technology and reliable service. Stay powered up with our stations!");
        View::assign('canonical',  Request::url(true));
        return view() ;
    }



    public function news(){
        if ($redirect = $this->handleRedirection(Request::url())) {
            return $redirect;
        }
        View::assign('site', Site::getOne(1) ) ;
        $data = SiteArticle::getOneW( ['is_home'=>1,'status'=>1] );
        View::assign('data', $data ) ;
        $list = SiteArticle::getListPO(['cate_id'=>1,'status'=>1],'*','id desc',15) ;
        View::assign('list', $list ) ;
        View::assign('tabs', 'news' ) ;

        View::assign('title', "News Center Collaboration & Updates | PowerOn SG");
        View::assign('description', "Stay updated with the latest news from PowerOn SG and explore collaboration opportunities for mobile phone charging solutions in Singapore.");
        View::assign('canonical',  Request::url(true));
        return view() ;
    }


    public function detail(){
        if ($redirect = $this->handleRedirection(Request::url())) {
            return $redirect;
        }
        View::assign('site', Site::getOne(1) ) ;
        $data = SiteArticle::getOne(input('id')) ;
        if (empty($data)) {
            return redirect('/home/index/news.html');
        }
        $title = $data['title'] ?? '';
        $contentdata = $data['content'] ?? '';
        View::assign('data', $data ) ;
        $list =  SiteArticle::getListPO(['cate_id'=>1,'status'=>1],'*','id desc',3) ;
        View::assign('list', $list ) ;
        View::assign('tabs', 'news' ) ;
        View::assign('title', 'PowerOn SG ' . $title );
        $content = preg_replace('/\s+/', ' ', strip_tags(preg_replace('/<img[^>]+\>/i', '', $contentdata)));
        $truncatedContent = mb_substr($content, 0, 150);
        View::assign('description', $truncatedContent);
        $canonicalUrl = strtok(Request::url(true), '?');
        View::assign('canonical', $canonicalUrl);
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