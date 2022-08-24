<?php

namespace Encore\PHPInfo\Http\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\InfoBox;
use DB;
use PDF;
use Encore\Admin\Widgets\Table;
use Jxlwqq\DataTable\DataTable;
use Illuminate\Http\Request;
use Encore\PHPInfo\Models\BamAcademicAttendance;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Auth\Database\Role;
use Encore\PHPInfo\Models\BamAcademicStudents;


class StockAjaxController extends Controller

{
    public function ajaxRequest(Request $request){
        if (isset($_GET['f'])) {
            $f = Bam_Secure($_GET['f'], 0);
        }
        if (isset($_GET['s'])) {
            $s = Bam_Secure($_GET['s'], 0);
        }
        if (isset($_POST['f'])) {
            $f = Bam_Secure($_POST['f'], 0);
        }
        if (isset($_POST['s'])) {
            $s = Bam_Secure($_POST['s'], 0);
        }
        $timestamp = date("Y-m-d H:i:s");

        $error_icon = '<i class="fa fa-exclamation-circle"></i> ';
        $success_icon = '<i class="fa fa-check"></i> ';
        $error = array();
        if($f == 'stock'){
            if($s == 'getitems'){
                $datas = DB::table('bam__items')->where('category_id',$request->id)->get();

                $item = '';
                foreach($datas as $items){
                    $item .= '<option value="'. $items->id.'">'.$items->item.'</option>';
                }
                $data  = array(
                    'status' => 200,
                    'message' => $item
                );
                header("Content-type: application/json");
                echo json_encode($data);
                exit();
            }
            if($s == 'getuserfromrole'){
                $role_id = $request->id;
                if($role_id == 'Student'){
                    $issue_to = '';
                    $students = BamAcademicStudents::where('is_completed','0')->where('school_id',Admin::user()->school_id)->get();
                  
                    foreach($students as $student){
                        $issue_to .= '<option value="'. $student->id.'">'.$student->first_name.' '.$student->admission_number.'</option>';
                    }
                }else{
                    $roleModel = config('admin.database.roles_model');
                    $roledata = $roleModel::findOrFail($role_id);
                    $role_name = $roledata->slug;

                    $role = Role::where('slug',$role_name)->first();
                    $users = $role->administrators->pluck('name', 'id');

                    $issue_to = '';
                    foreach($users as $key=> $value){
                        $issue_to .= '<option value="'. $key.'">'.$value.'</option>';
                    }
            
                    
                }
                $data  = array(
                    'status' => 200,
                    'message' => $issue_to
                );
                header("Content-type: application/json");
                echo json_encode($data);
                exit();
            }
        }
    }
}