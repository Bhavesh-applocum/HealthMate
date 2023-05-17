<div class="d-flex align-items-center">
    <a  class="mr-2 cursor-pointer icon-btn icon-btn-primary-red icon-btn icon-btn __shadow-lg icon-btn-hover-outline no-bg delete-btn" data-id="{{ $row->id }}" data-url="{{ route('admin.candidates.delete',$row->id) }}">
        <i class="fa-regular fa-trash-can"></i>
    </a>
    <a class="mr-2 cursor-pointer icon-btn icon-btn-primary-green icon-btn icon-btn __shadow-lg icon-btn-hover-outline no-bg candidate-view-btn" data-id="{{ $row->id }}" data-url="{{ route('admin.candidates.show',$row->id) }}">
        <i class="fa-regular fa-eye"></i>
    </a>
</div>