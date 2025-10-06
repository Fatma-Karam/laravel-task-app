



@extends('layouts.app')

@section('title', 'قائمة المهام')

@section('content')
<div class="container mt-4">

  {{-- ✅ عرض رسالة النجاح --}}
  @if(session('success'))
  <div class="alert alert-success text-center">{{ session('success') }}</div>
  @endif

  <div class="row">
    {{-- 🧾 فورم إضافة / تعديل مهمة --}}
    <div class="col-md-5">
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
          {{ isset($task) ? 'تعديل مهمة' : 'إضافة مهمة جديدة' }}
        </div>
        <div class="card-body">
          <form method="POST" action="{{ isset($task) ? route('tasks.update', $task->id) : route('tasks.store') }}">
            @csrf
            @if(isset($task))
              @method('PUT')
            @endif

            <div class="mb-3">
              <label class="form-label">العنوان</label>
              <input type="text" name="title" class="form-control" value="{{ $task->title ?? '' }}">
            </div>

            <div class="mb-3">
              <label class="form-label">الوصف</label>
              <textarea name="description" class="form-control">{{ $task->description ?? '' }}</textarea>
            </div>

            @if(isset($task))
            <div class="mb-3">
              <label class="form-label">الحالة</label>
              <select name="status" class="form-select">
                <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>قيد التنفيذ</option>
                <option value="done" {{ $task->status == 'done' ? 'selected' : '' }}>مكتملة</option>
              </select>
            </div>
            @endif

            <div class="d-flex gap-2">
              <button class="btn btn-primary">{{ isset($task) ? 'تحديث' : 'إضافة' }}</button>
              @if(isset($task))
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">إلغاء</a>
              @endif
            </div>
          </form>
        </div>
      </div>
    </div>

    {{-- 📋 قائمة المهام --}}
    <div class="col-md-7">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>قائمة المهام</h4>

        {{-- 🔍 فلتر الحالة --}}
        <form method="GET" action="{{ route('tasks.index') }}" class="d-flex gap-2">
          <select name="status" class="form-select w-auto">
            <option value="">الكل</option>
            <option value="pending" {{ isset($task) && $task->status=='pending' ? 'selected' : '' }}>قيد التنفيذ</option>
<option value="done" {{ isset($task) && $task->status=='done' ? 'selected' : '' }}>مكتملة</option>

       
          </select>
          <button class="btn btn-outline-secondary">تصفية</button>
        </form>
      </div>

      <div class="row">
        @forelse($tasks as $t)
        <div class="col-md-6 mb-3">
          <div class="card shadow-sm border-{{ $t->status == 'done' ? 'success' : 'warning' }}">
            <div class="card-body">
              <h5 class="card-title">{{ $t->title }}</h5>
              <p class="card-text">{{ $t->description }}</p>
              <span class="badge bg-{{ $t->status == 'done' ? 'success' : 'warning' }}">
                {{ $t->status == 'done' ? 'مكتملة' : 'قيد التنفيذ' }}
              </span>

              <p class="text-muted small mt-2">
                <i class="bi bi-calendar-event"></i>
                {{ $t->created_at->format('Y-m-d H:i') }}
              </p>

              <div class="d-flex gap-2 mt-2">
                <a href="{{ route('tasks.edit', $t->id) }}" class="btn btn-sm btn-outline-info">
                  <i class="bi bi-pencil-square"></i> تعديل
                </a>

                {{-- 🔄 تبديل الحالة بسرعة --}}
                <form method="POST" action="{{ route('tasks.update', $t->id) }}">
                  @csrf
                  @method('PUT')
                  <input type="hidden" name="title" value="{{ $t->title }}">
                  <input type="hidden" name="description" value="{{ $t->description }}">
                  <input type="hidden" name="status" value="{{ $t->status == 'pending' ? 'done' : 'pending' }}">
                  <button class="btn btn-sm btn-outline-success">
                    {{ $t->status == 'pending' ? 'إنهاء' : 'إعادة' }}
                  </button>
                </form>

                <form method="POST" action="{{ route('tasks.destroy', $t->id) }}">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash"></i> حذف
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
        @empty
        <p class="text-center text-muted">لا توجد مهام حالياً</p>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection
