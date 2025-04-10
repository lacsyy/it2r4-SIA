<?php
 
 namespace App\Http\Controllers;
 
 use App\Models\User;
 use Illuminate\Http\Response;
 use Illuminate\Http\Request;
 use App\Traits\ApiResponser;
 use DB;
 
 class UserController extends Controller
 {
     use ApiResponser;
     private $request;
 
     public function __construct(Request $request)
     {
         $this->request = $request;
     }
 
     public function getUsers()
     {
         $users = DB::connection('mysql')->select("Select * from tbluser");
         return $this->successResponse($users);
     }
 
     public function index()
     {
         $users = User::all();
         return $this->successResponse($users);
     }
 
     public function addUser(Request $request)
     {
         $rules = [
             'username' => 'required|max:20',
             'password' => 'required|max:20',
             'gender' => 'required|in:Male,Female',
         ];
         $this->validate($request, $rules);
         $user = User::create($request->all());
         return $this->successResponse($user, Response::HTTP_CREATED);
     }
 
     public function show($id)
     {
         $user = User::findOrFail($id);
         return $this->successResponse($user);
     }
 
     public function update(Request $request, $id)
     {
         $rules = [
             'username' => 'max:20',
             'password' => 'max:20',
             'gender' => 'in:Male,Female',
         ];
 
         $this->validate($request, $rules);
         $user = User::findOrFail($id);
         $user->fill($request->all());
 
         if ($user->isClean()) {
             return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
         }
         $user->save();
         return $this->successResponse($user);
     }
 
     public function delete($id)
     {
         $user = User::findOrFail($id);
         $user->delete();
 
         return $this->successResponse('User deleted successfully');
     }
 
 }