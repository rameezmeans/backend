<!DOCTYPE html>
<html>
<head>
    <title>Schedule Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<div class="container mt-4">
    <h2>Schedule Management</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Schedule Form -->
    <form action="{{ route('schedule.store') }}" method="POST" class="card p-3 mb-4">
        @csrf
        <div class="row g-3">
            <div class="col-md-3">
                <label>Day</label>
                <select name="day" class="form-control" required>
                    <option value="">Select Day</option>
                    @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                        <option value="{{ $day }}">{{ $day }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Date</label>
                <input type="date" name="date" class="form-control" required>
            </div>
            <div class="col-md-1">
                <label>Start</label>
                <input type="time" name="start_time" class="form-control" required>
            </div>
            <div class="col-md-1">
                <label>End</label>
                <input type="time" name="end_time" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label>Course</label>
                <select name="course" class="form-control" required>
                    <option value="">Select Course</option>
                    <option value="Database">Database (101)</option>
                    <option value="Software Engineering">Software Engineering (102)</option>
                    <option value="Computer Networks">Computer Networks (103)</option>
                    <option value="AI">AI (104)</option>
                    <option value="physics">Physics (105)</option>
                    <option value="IT Security">IT Security (106)</option>
                    <option value="bussiness">Business (107)</option>
                    <option value="phycology">Psychology (108)</option>
                    <option value="linear algebra">Linear Algebra (109)</option>
                    <option value="english composition">English Composition (110)</option>
                    <option value="c++">C++ (111)</option>
                    <option value="data structure">Data Structure (112)</option>
                    <option value="System and network administrator">System and Network Administrator (113)</option>
                    <option value="HRM">HRM (114)</option>
                </select>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button class="btn btn-primary w-100">Add</button>
            </div>
        </div>
    </form>

    <!-- Schedule Table -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Day</th>
                <th>Date</th>
                <th>Course</th>
                <th>Course ID</th>
                <th>Time</th>
                <th>Faculty</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedules as $schedule)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $schedule->day }}</td>
                    <td>{{ $schedule->date }}</td>
                    <td>{{ $schedule->course }}</td>
                    <td>{{ $schedule->course_id ?? 'N/A' }}</td>
                    <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                    <td>
                        @if($schedule->faculties->count())
                            {{ $schedule->faculties->pluck('full_name')->join(', ') }}
                        @else
                            <em>No faculty assigned</em>
                        @endif
                    </td>
                    <td class="d-flex gap-2">
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#assignFacultyModal{{ $schedule->id }}">
                            Add Faculty
                        </button>
                        <form action="{{ route('schedule.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>

                <!-- Assign Faculty Modal -->
<div class="modal fade" id="assignFacultyModal{{ $schedule->id }}" tabindex="-1" aria-labelledby="assignFacultyLabel{{ $schedule->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('schedule.assignFaculty', $schedule->id) }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="assignFacultyLabel{{ $schedule->id }}">
                    Assign Faculty to {{ $schedule->course }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="facultySelect{{ $schedule->id }}" class="form-label">Select Faculty</label>
                    <select id="facultySelect{{ $schedule->id }}" name="faculty_ids[]" class="form-control faculty-select" multiple="multiple" required>
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}"
                                {{ $schedule->faculties->contains($faculty->id) ? 'selected' : '' }}>
                                {{ $faculty->full_name }} ({{ $faculty->department }})
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">You can select multiple faculty members.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Assign Faculty</button>
            </div>
        </form>
    </div>
</div>

            @endforeach
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $('#assignFacultyModal{{ $schedule->id }}').on('shown.bs.modal', function () {
        $(this).find('.faculty-select').select2({
            placeholder: "Select faculty",
            allowClear: true,
            width: '100%',
            dropdownParent: $(this)
        });
    });
</script>

</body>
</html>