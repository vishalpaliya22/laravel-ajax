<?php
         
namespace App\Http\Controllers;
          
use App\Models\Employee;
use App\Models\Role;
use Illuminate\Http\Request;
use DataTables;
        
class EmployeesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
   
        $employees = Employee::latest()->get();
        
        $userRole = Role::latest()->get();
        if ($request->ajax()) {
            // $data = Employee::latest()->get();
            $data = \DB::table('roles')
                ->join('employees AS emp', 'emp.role_id', '=', 'roles.id')
                ->get(['roles.name as roleName', 'emp.*']);
            // dd($data);
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
   
                           $viewbtn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Show" class="show btn btn-primary btn-sm showEmployee">Show</a>';

                           $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editEmployee">Edit</a>';
   
                           $btn = $viewbtn. $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteEmployee">Delete</a>';
    
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
      
        return view('employee',compact('employees','userRole'));
    }
     
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $imagePath = $request->file('photo');
        $saveImage = '';
        if($imagePath){
            $imagesName = $imagePath->getClientOriginalName();
            $path = $imagePath->storeAs('upload',$imagesName,'public');
            $saveImage = '/storage/'.$path;
        }
        // dd($data2);
        Employee::updateOrCreate(['id' => $request->employee_id],
                ['role_id' => $request->role_id, 'name' => $request->name, 'profile_pic' => $saveImage, 'email' => $request->email, 'phone_number' => $request->phone_no, 'gender' => $request->gender, 'address' => $request->address, 'status' => $request->status]);        
        
        if($request->employee_id != '')
            return response()->json(['success'=>'Employee update successfully.']);
            
        return response()->json(['success'=>'Employee saved successfully.']);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Employee::find($id);
        return response()->json($employee);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::find($id);

        return response()->json($employee);
    }
  
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Employee::find($id)->delete();
     
        return response()->json(['success'=>'Employee deleted successfully.']);
    }
}