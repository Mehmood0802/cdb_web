<?php 
declare(strict_types = 1);

namespace app\admin\controller ;

use app\admin\Controller ;
use think\facade\View ;






use app\admin\service\Admin as AdminService ;

Class Index extends Controller{

	public function index(){

		if(!$this->admin){	
			return redirect('public/login');
		}

		View::assign('uri', $this->uri ) ;
		View::assign('admin', $this->admin ) ;
        View::assign('list', AdminService::emnuList( $this->admin['role_id'] ) ) ;

		return view();
	}

	public function left(){
		return view();
	}

	public function main(){




		View::assign('list', []  ) ;
		View::assign('page', input('page') );
		View::assign('uri', $this->uri );


		return view();
	}

}








