<table class="table">
    <thead class="bg-light">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Title</th>
            <th scope="col">Created By</th>
            <th scope="col">Status</th>
            <th scope="col">Date</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody class="border-0">
        @if ($jobs->isNotEmpty())
            @foreach ($jobs as $job)
            <tr>
                <td>{{ $job->id }}</td>
                <td>
                    <p>{{ $job->job_title }}</p>
                    <p>Applicants: {{ $job->applications->count() }}</p>
                </td>
                <td>{{ $job->user->name }}</td>
                <td>
                    @if ($job->status == 1)
                        <p class="text-success">Active</p>
                    @else
                        <p class="text-danger">Block</p>
                    @endif
                </td>
                <td>{{ $job->formatted_created_at }}</td>
                <td>
                    <div class="action-dots ">
                        <button href="#" class="btn" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('admin.jobs.edit',$job->id) }}"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a></li>
                            <li><a class="dropdown-item" onclick="deleteJob({{ $job->id }})" href="javascript:void(0);"  ><i class="fa fa-trash" aria-hidden="true"></i> Delete</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>                                
</table>