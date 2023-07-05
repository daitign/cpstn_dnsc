<div class="col-2 text-center">
    <button 
        data-toggle="tooltip" title="{{ $directory->fullPath() ?? '' }}" 
        class="btn align-items-center justify-content-center btn-directory" 
        data-bs-toggle="dropdown" aria-expanded="false" 
        data-route="{{ route($route ?? 'archives-page') }}?directory={{ $directory->id }}">
            <img src="{{ Storage::url('assets/folder.png') }}" alt="Folder.png" class="img-fluid">
            <p class="text-white" style="text-overflow: ellipsis"><small>{{ $directory->name ?? '' }}</small></p>
    </button>
    <ul class="dropdown-menu text-center">
        <li><a href="{{ route($route ?? 'archives-page') }}?directory={{ $directory->id }}" class="text-decoration-none">Open Directory</a></li>
        <li><a href="#" class="text-decoration-none btn-property"
            data-bs-toggle="modal" data-bs-target="#propertyModal"
            data-name="{{ $directory->name }}"
            data-type="Directory"
            data-created-by="{{ $directory->user->username ?? 'Admin' }}"
            data-created-at="{{ $directory->created_at ? $directory->created_at->format('M d, Y h:i A') : '' }}"
            data-updated-at="{{ $directory->created_at ? $directory->created_at->format('M d, Y h:i A') : '' }}"
        >Properties</a></li>
        
        @if(Auth::user()->role->role_name == 'Document Control Custodian' && !empty($current_directory->area) && $current_directory->area->type == 'process')
        <li>
            <a href="#" class="text-decoration-none toggleDirectoryModal"
                data-name="{{ $directory->name }}" 
                data-route="{{ route('archives-update-directory', $directory->id) }}" 
                data-bs-toggle="modal" data-bs-target="#directoryModal">
                    Rename
            </a>
        </li>
        <!-- <li>
            <a href="#" class="text-decoration-none btn-confirm" data-target="#delete_directory_{{ $directory->id }}">Delete</button>
                <form id="delete_directory_{{ $directory->id }}" action="{{ route('archives-delete-directory', $directory->id) }}" class="d-none" method="POST">
                    @csrf
                    @method('DELETE')
                </form>
            </a>
        </li> -->
        @endif
    </ul>
</div>