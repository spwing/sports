<?php namespace App\Http\Controllers;

use Request;
use App\User;
use App\Blog;
use Session;
use DB;
use Hash;
use URL;

class ProjectController extends Controller {
	
	public function getTiny() {
		return view('tiny');
	}

	public function getIndex() {
		if(!Session::get('email')) {
			$error = "";
			$email = "";
			$password = "";
			if(Session::get('email')) {
				Session::forget('email');
			}
			return view('login')->with('email', $email)->with('password', $password)->with('error',$error);
		}
		else {
			$sections = ['news'=>'News', 'sports'=>'Sports', 'opinions'=>'Opinions', 'aande'=>'A&E'];
			$blogs = DB::table('blogs')->orderBy('created_at', 'desc')->get();
			return view('index')->with('sections', $sections)->with('blogs',$blogs);
		}
	}
	
	public function getSignUp() {
		$error = "";
		$email = "";
		$password = "";
		$cpassword = "";
		$msg = "";
		if(Session::get('email')) {
			Session::forget('email');
		}
		return view('signup')->with('error',$error)->with('email',$email)->with('password',$password)->with('cpassword',$cpassword)->with('msg', $msg);
	}
	public function postSignUp() {
		$error = "";
		$msg = "";
		$verified = false;
		
		$email = Request::input('email');
		$password = Request::input('password');
		$cpassword = Request::input('cpassword');
		
		if($email == 'anvil@mxschool.edu') {
			$verified = true;
		}
		$users = DB::table('users')->get();
		$errors = false;
		
		$usernameArray = array();
		foreach($users as $user) {
			array_push($usernameArray, $user->email);
		}
		
		
		if($email == "" || $password == "") {
			$error = "Please fill in all fields.";
			$errors = true;
		}
		elseif(!(filter_var($email, FILTER_VALIDATE_EMAIL))) {
			$error = "Please use a valid email.";
			$errors = true;
		}
		elseif(in_array($email, $usernameArray)) {
			$error = "User already exists.";
			$errors = true;
		}
		elseif(strlen($password) < 6) {
			$error = "Your password must be at least 6 letters.";
			$errors = true;
		}
		elseif(!(preg_match('#[0-9]#', $password)) || !(preg_match('#[a-zA-Z]#', $password))) {
			$error = "Your password must contain a letter and a number.";
			$errors = true;
		}
		elseif(!($password == $cpassword)) {
			$error = "Please make sure your passwords match.";
			$errors = true;
		}
		
		if(!$errors) {
			
		$hash = md5( rand(0,1000) );
			
			
			$blogs = DB::table('blogs')->orderBy('created_at', 'desc')->get();
			
			$passhash = Hash::make($password);
			$user = new User;
			$user->email = $email;
			$user->password = $passhash;
			$user->verified = $verified;
			$user->hash = $hash;
			$user->verifiedE = $verified;
			$user->push();
		
			
			$to      = $email; // Send email to our user
			$subject = 'Signup for Anvil | Verification'; // Give the email a subject 
			$message = '
 
Your anvil account has been created. You can login with the following credentials after you have activated your account by pressing the url below.
			 
Please click this link to activate your account:
		
anvil.mxschool.edu/'.$email.'/'.$hash.'/verify
		
			 
		'; // Our message above including the link
								 
			mail($to, $subject, $message); // Send our email

			$msg = 'Your anvil account has been made. Please verify your account by clicking the link sent to your email.';
			return view('signup')->with('error',$error)->with('email',$email)->with('password', $password)->with('cpassword',$cpassword)->with('msg',$msg);
		
		}
		else{
			return view('signup')->with('error',$error)->with('email',$email)->with('password', $password)->with('cpassword',$cpassword)->with('msg',$msg);
		}
		
		
	}
	
	
	public function verify($email, $hash) {
		$userArray = DB::table('users')->where('email',$email)->get();
		$user = $userArray[0];
		$dbhash = $user->hash;
		if($hash == $dbhash) {
			DB::table('users')->where('email',$email)->update(['verifiedE' => 1]);
		}
		return redirect('verified');
	}
	
	public function getVerify() {
		return view('verify');
	}
	
	public function getForgot() {
		$msg = "";
		$error = "";
		$email = "";
		if(Session::get('email')) {
			Session::forget('email');
		}
		return view('forgot')->with('email',$email)->with('error',$error)->with('msg',$msg);
	}
	
	public function postForgot() {
		$msg = "";
		$error = "";
		$email = Request::input('email');
		
		
		if(!$email) {
			$error = "Please fill in all fields";
			return view('forgot')->with('email', $email)->with('error',$error)->with('msg',$msg);
		}
		else {
			$userArray = DB::table('users')->where('email',$email)->get();
			$user = $userArray[0];
			$dbhash = $user->hash;
			if(!$userArray){
				$error = "Sorry, user does not exist.";
				return view('forgot')->with('email', $email)->with('error',$error)->with('msg',$msg);
			}
			else {
				$to      = $email; // Send email to our user
				$hash = $dbhash;
			$subject = 'Anvil | Password Reset'; // Give the email a subject 
			$message = '
			 
Please click this link to reset your password:
		
anvil.mxschool.edu/'.$email.'/'.$hash.'/reset
		
			 
		'; // Our message above including the link
								 
			mail($to, $subject, $message); // Send our email

			$msg = 'A message with a reset password link has been sent to your email.';
			return view('forgot')->with('error',$error)->with('email',$email)->with('msg',$msg);
				
				
			}
		}
		
	}
	
	public function getReset($email, $hash) {
		$error = "";
		$userArray = DB::table('users')->where('email',$email)->get();
		$user = $userArray[0];
		$dbhash = $user->hash;
		if($hash == $dbhash) {
			return view('reset')->with('email', $email)->with('hash', $hash)->with('error',$error);
		}
	}
	
	public function postReset($email, $hash) {
		$userArray = DB::table('users')->where('email',$email)->get();
		$user = $userArray[0];
		$dbhash = $user->hash;
		if($hash == $dbhash) {
			$password = Request::input('password');
			$cpassword = Request::input('cpassword');
			
			if($password == "" || $cpassword == "") {
				$error = "Please fill in all fields.";
			}
			elseif(strlen($password) < 6) {
				$error = "Your password must be at least 6 letters.";
			}
			elseif(!(preg_match('#[0-9]#', $password)) || !(preg_match('#[a-zA-Z]#', $password))) {
				$error = "Your password must contain a letter and a number.";
			}
			elseif(!($password == $cpassword)) {
				$error = "Please make sure your passwords match.";
				$errors = true;
			}
			else {
				$passhash = Hash::make($password);
				DB::table('users')->where('email',$email)->update(['password' => $passhash]);
				return redirect('/');
			}
		}
		
		

		
	}

	
	public function getUsers() {
		if(!(Session::get('email')) || Session::get('email') != "anvil@mxschool.edu") {
			return view('sports');
		}
		else {
			$users = DB::table('users')->orderBy('created_at', 'desc')->get();
			return view('users')->with('users',$users);
		}	
	}

	public function postUsers() {
		$email = Request::input('users');
		$users = DB::table('users')->get();
		foreach($users as $user) {
			if($user->email == $email) {
				DB::table('users')->where('email',$email)->update(['verified' => 1]);
			}
		}
		return redirect('/');
	}
	
	public function getDeactivate() {
		if(!(Session::get('email')) || Session::get('email') != "anvil@mxschool.edu") {
			return view('sports');
		}
		else {
			$users = DB::table('users')->orderBy('created_at', 'desc')->get();
			return view('deactivate')->with('users',$users);
		}
	}
	
	public function postDeactivate() {
		$email = Request::input('users');
		$users = DB::table('users')->get();
		foreach($users as $user) {
			if($user->email == $email) {
				DB::table('users')->where('email',$email)->update(['verified' => 0]);
			}
		}
		return redirect('/');
	}
	
	public function getAccounts() {
		if(!(Session::get('email')) || Session::get('email') != "anvil@mxschool.edu") {
			return view('sports');
		}
		else {
			$users = DB::table('users')->orderBy('created_at', 'desc')->get();
			return view('deleteUser')->with('users',$users);
		}	
	}
	
	public function postAccounts() {
		$email = Request::input('users');
		$users = DB::table('users')->get();
		foreach($users as $user) {
			if($user->email == $email) {
				DB::table('users')->where('email',$email)->delete();
			}
		}
		return redirect('/');
	}
	public function getLogIn() {
		$error = "";
		$email = "";
		$password = "";
		if(Session::get('email')) {
			Session::forget('email');
		}
		return view('login')->with('email',$email)->with('password', $password)->with('error',$error);
	}
	
	public function postLogIn() {
		$email = Request::input('email');
		$password = Request::input('password');
		if(!$email || !$password) {
			$error = "Please fill in all fields";
			return view('login')->with('email', $email)->with('password', $password)->with('error',$error);
		}
		else {
			$userArray = DB::table('users')->where('email',$email)->get();
			if(!$userArray){
				$error = "Sorry, user does not exist.";
				return view('login')->with('email', $email)->with('password', $password)->with('error',$error);
			}
			
			else {
				$user = $userArray[0];
				$dbpassword = $user->password;
				if(!Hash::check($password, $dbpassword)) {
					$error = "Incorrect Password";
					$password = "";
					return view('login')->with('email', $email)->with('password', $password)->with('error',$error);
				}
				elseif($user->verifiedE == false) {
					$error = "You have not yet verified your email";
					return view('login')->with('email', $email)->with('password', $password)->with('error',$error);
				}
				elseif($user->verified == false ) {
					$error = "The Anvil has not yet verified you. Try again later!";
					return view('login')->with('email', $email)->with('password', $password)->with('error',$error);
				}
				else {
					Session::set('email',$email);
					$blogs = DB::table('blogs')->orderBy('created_at', 'desc')->get();
					return redirect('/');
				}
			}
		}
	}
	
	public function getRepost($i) {
		if(!Session::get('email')) {
			$email = "";
			$password = "";
			$error = "Please log in before you post";
			return view('login')->with('email', $email)->with('password', $password)->with('error',$error);
		}
		else {
			$x = 1;
			$blogs = DB::table('blogs')->orderBy('created_at', 'desc')->get();
			$sections = ['news'=>'News', 'sports'=>'Sports', 'opinions'=>'Opinions', 'aande'=>'A&E'];
			foreach($blogs as $blog) {
				if($x == $i) {
					$error = "";
					$link = $blog->link;
					$sectionNorm = $blog->section;
					$section = $sections[$sectionNorm];
					$subject = $blog->subject;
					$content = $blog->content;
					return view('repost')->with('i', $i)->with('link',$link)->with('section',$section)->with('sectionNorm',$sectionNorm)->with('subject',$subject)->with('content',$content)->with('error',$error);
				}
				$x = $x+1;
			}
		}
	}
	
	public function postRepost($i) {
		$link = Request::input('link');
		$section = Request::input('section');
		$subject = Request::input('post_name');
		$content = Request::input('post');
		if(!$subject || !$content) {
			$error = "Please fill in all fields";
			return view('repost')->with('link',$link)->with('error',$error)->with('subject',$subject)->with('content',$content);
		}
		else {
			$x = 1;
			$blogs = DB::table('blogs')->orderBy('created_at', 'desc')->get();
			foreach($blogs as $blog) {
				if($x == $i) {
					$created_at = $blog->created_at;
					DB::table('blogs')->where('created_at',$created_at)->update(['content' => $content]);
					DB::table('blogs')->where('created_at',$created_at)->update(['link' => $link]);
					DB::table('blogs')->where('created_at',$created_at)->update(['section' => $section]);
					DB::table('blogs')->where('created_at',$created_at)->update(['subject' => $subject]);
				}
				$x = $x+1;
			}
		}
		return redirect('/');
	}
	
	public function getDelete($i) {
		$blogs = DB::table('blogs')->orderBy('created_at', 'desc')->get();
		$x = 1;
		foreach($blogs as $blog) {
			if($x == $i) {
				$subject = $blog->subject;
				DB::table('blogs')->where('subject',$subject)->delete();
			}
			$x = $x+1;
		}
		return redirect('/');
	}
	public function getNew() {
		$email = "";
		$password = "";
		if(!Session::get('email')) {
			$error = "Please log in before you post";
			return view('login')->with('email', $email)->with('password', $password)->with('error',$error);
		}
		else {
			$error = "";
			$subject = "";
			$content = "";
			$link = "";
			return view('newpost')->with('link',$link)->with('error',$error)->with('subject',$subject)->with('content',$content);
		}
	}
	
	public function postNew() {
		$link = Request::input('link');
		$section = Request::input('section');
		$subject = Request::input('post_name');
		$content = Request::input('post');
		$user = Session::get('email');
		if(!$subject || !$content) {
			$error = "Please fill in all fields";
			return view('newpost')->with('link',$link)->with('error',$error)->with('subject',$subject)->with('content',$content);
		}
		else {
			$blog = new Blog;
			$blog->section = $section;
			$blog->link = $link;	
			$blog->user = $user;
			$blog->subject = $subject;
			$blog->content = $content;
			$blog->push();
			
			return redirect('/');
		}
	}
	
	public function logout() {
		if(Session::get('email')) {
			Session::forget('email');
		}
		$blogs = DB::table('blogs')->orderBy('created_at', 'desc')->get();
		return redirect('/');
	}
	
	
	/*************************************
	 *This is really bad! Separate this in
	 *the future into it's own class.
	**************************************/

	
}