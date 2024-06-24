<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
class BranchController extends Controller
{
    public function index(){
        $branchs=branch::with('departments')->latest()->get();
         
      
        return response()->json(
            $branchs
            ,200);
    }
    public function store(Request $request){
        
        try{
            
              
            $validatebranch = Validator::make($request->all(), 
            [
               'name' => 'string|required|unique:branchs'
            ]);
           
            if($validatebranch->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'خطأ في التحقق',
                    'errors' => $validatebranch->errors()
                ], 422);
            }
           
            $branch = branch::create(array_merge(
                $validatebranch->validated()
                
                ));
          
        
            $result=$branch->save();
           if ($result){
               
                return response()->json(
                    ['status' => true,
                    'message' =>    'تم أضافة  بنجاح',
                    'data'=>null]
                 , 201);
                }
           else{
                return response()->json(
                    ['status' => false,
                    'message' =>'حدث خطأ أثناء أضافة البيانات',
                    'data'=>null],
                    422);
                }

        }
        catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' =>  $th->getMessage(),
                // "حدث خطأ أثناء أضافة البيانات"
            ], 500);
        }
       
        
    }
    public function destroy($id){
        try {  
            
            $input = [ 'id' =>$id ];
            $validate = Validator::make( $input,
                ['id'=>'required|integer|exists:branchs,id']);
            if($validate->fails()){
            return response()->json([
               'status' => false,
               'message' => 'خطأ في التحقق',
               'errors' => $validate->errors()
            ], 422);}
          
            $branch=branch::find($id);
        
           
          if($branch){ 
               
                $users= $branch->users()->get();
                
                foreach($users as $user){
                    $branch->users()->detach($user->id);
                    $user->save();
                }
                
                $result= $branch->delete();
                if($result){ 
                    return response()->json(
                    ['message'=>' تم حذف بنجاح']
                    , 200);
                }
            }

            
                return response()->json(
                    ['status' => false,
                    'message' =>'حدث خطأ أثناء أضافة البيانات',
                    'data'=>null],
                    422);
              
        }
        catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } 
        catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the D.'], 500);
        }
    }
    public function update(Request $request){
        try{
            
    
            
            $validatebranch = Validator::make($request->all(), [
               'id'=>'required|integer|exists:branchs,id',
               'name' => 'nullable|string|unique:branchs'
            ]);
           
           
            
            if($validatebranch->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'خطأ في التحقق',
                    'errors' => $validatebranch->errors()
                ], 422);
            }
            $branch=branch::find($request->id);
            
            if($branch){  
                $branch->update($validatebranch->validated());
                $branch->save();
                }
              
                
                return response()->json(
                    ['status' => true,
                    'message' =>    'تم تعديل بنجاح',
                    'data'=>null]
                 , 200);
                
      
            return response()->json([
                'status' => false,
                'message' =>  'فشلت عملية التعديل ',
                'data'=> null
                ], 422);
            

        }
        catch (\Throwable $th) {
            return response()->json([
            'status' => false,
            'message' => $th->getMessage()
            ], 500);
        }
      
        
    }
 
    public function search($search){
        try {
                
            $input = [ 'search' =>$search ];
               
            $validatesearch = Validator::make($input, 
            [ 'search' => 'required|string|min:3' ]); 
                
            if($validatesearch->fails()  ){
                    return response()->json([
                        'status' => false,
                         'message' => 'خطأ في التحقق',
                        'errors' => $validatesearch->errors()
                    ], 422);
                    }
          
            $data = branch::with('users')->where('name','LIKE', '%' . $search .'%')->get();      
              
            
            if(count($data)>0)
            {
                $result=array();
                
                foreach($data as $branch){
                    
                    if(! in_array($branch,$result)  ){
                        array_push($result , $branch);
                        
                    }
                }
                
                if ($result)
                { return response()->json(
                            
                    $result
                    , 200);  
                }
            }
            else{
                return response()->json([],204); 
                }
        }
        catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } 
        catch (\Exception $e) {
            return response()->json(['message' => 'An error  occurred while requesting this Product.'], 500);
        }

    }
    public function show( $id) {
       
        try {  
            
            $input = [ 'id' =>$id];
            $validate = Validator::make( $input,
                ['id'=>'required|integer|exists:branchs,id']);
            if($validate->fails()){
            return response()->json([
               'status' => false,
               'message' => 'خطأ في التحقق',
               'errors' => $validate->errors()
            ], 422);}
        //   branchh relishen shipe
        
            $branch=branch::find($id);
          
          if($branch){ 
            return response()->json(
                $branch
                 , 200);
            } 
                 
              
               
               
            

            return response()->json(['message'=>" حدث خطأ أثناء عملية جلب البيانات "], 422);
        }
        catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } 
        catch (\Exception $e) {
            return response()->json(['message' =>$e
            //  'حدث خطأ أثناء عملية جلب البيانات'
            ], 
             500);
        }
    }  //
}