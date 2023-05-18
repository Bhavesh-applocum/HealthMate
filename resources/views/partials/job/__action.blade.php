<div class="d-flex align-items-center">
    <a  class="mr-2 cursor-pointer icon-btn icon-btn-primary-red icon-btn icon-btn __shadow-lg icon-btn-hover-outline no-bg delete-btn" data-id="{{ $row->id }}" data-url="{{ route('admin.jobs.delete', $row->id) }}">
        <i class="fa-regular fa-trash-can"></i>
    </a>
    <a href="{{ route('admin.jobs.show', $row->id) }}   " class="mr-2 cursor-pointer icon-btn icon-btn-primary-green icon-btn icon-btn __shadow-lg icon-btn-hover-outline no-bg job-view-btn" >
        <i class="fa-regular fa-eye"></i>
    </a>
</div>