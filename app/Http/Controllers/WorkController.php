<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
class WorkController extends Controller
{
    public function index(){
        $works=work::latest()->get();
        return response()->json(
            $works
            ,200);
    }
    public function store(Request $request){
        
        try{
            
              
            $validatework = Validator::make($request->all(), 
            [
               'name' => 'string|required|unique:works'
            ]);
           
            if($validatework->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'خطأ في التحقق',
                    'errors' => $validatework->errors()
                ], 422);
            }
           
            $work = work::create(array_merge(
                $validatework->validated()
                
                ));
          
        
            $result=$work->save();
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
                ['id'=>'required|integer|exists:works,id']);
            if($validate->fails()){
            return response()->json([
               'status' => false,
               'message' => 'خطأ في التحقق',
               'errors' => $validate->errors()
            ], 422);}
          
            $work=work::find($id);
        
           
          if($work){ 
               
                $users= $work->users()->get();
                
                foreach($users as $user){
                    $work->users()->detach($user->id);
                    $user->save();
                }
                
                $result= $work->delete();
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
            
    
            
            $validatework = Validator::make($request->all(), [
               'id'=>'required|integer|exists:works,id',
               'name' => 'nullable|string|unique:works'
            ]);
           
           
            
            if($validatework->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'خطأ في التحقق',
                    'errors' => $validatework->errors()
                ], 422);
            }
            $work=work::find($request->id);
            
            if($work){  
                $work->update($validatework->validated());
                $work->save();
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
          
            $data = work::with('users')->where('name','LIKE', '%' . $search .'%')->get();      
              
            
            if(count($data)>0)
            {
                $result=array();
                
                foreach($data as $work){
                    
                    if(! in_array($work,$result)  ){
                        array_push($result , $work);
                        
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
                ['id'=>'required|integer|exists:works,id']);
            if($validate->fails()){
            return response()->json([
               'status' => false,
               'message' => 'خطأ في التحقق',
               'errors' => $validate->errors()
            ], 422);}
        //   branch relishen shipe
        
            $work=work::find($id);
          
          if($work){ 
            return response()->json(
                $work
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
    }
}