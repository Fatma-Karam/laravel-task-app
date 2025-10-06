<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
 public function index(Request $request){

      // لو المستخدم اختار فلتر (مكتملة / قيد التنفيذ)
        $filter = $request->get('status');

        // نجيب المهام بناءً على الفلتر لو موجود
        $tasks = Task::when($filter, function ($query, $filter) {
            return $query->where('status', $filter);
        })->orderBy('created_at', 'desc')->get();

        return view('tasks.index', compact('tasks', 'filter'));


  
 }



 public function store(Request $request){
    $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $data['status'] = 'pending'; // الحالة الافتراضية
        Task::create($data);

        // رسالة نجاح
        return redirect()->route('tasks.index')->with('success', 'تم إضافة المهمة بنجاح ✅');
  
}

public function edit($id){
    $task = Task::findOrFail($id);
    $tasks = Task::orderBy('created_at', 'desc')->get();
     return view('tasks.index', compact('tasks', 'task'));
 



}
public function update(Request $request,Task $task){
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,done',
        ]);

        $task->update($data);
        return redirect()->route('tasks.index')->with('success', 'تم تحديث المهمة بنجاح ✏️');
  

}



public function destroy(Task $task){
     $task->delete();
        return redirect()->route('tasks.index')->with('success', 'تم حذف المهمة 🗑️');
 
}
}

