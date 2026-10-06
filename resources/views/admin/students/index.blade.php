@extends('layouts.admin')

@section('title', 'Students')

@section('content')

    <h2 class="mb-4">Students</h2>

    <div class="card shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>College</th>
                            <th>Qualification</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($students as $student)

                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    {{ $student->user->name }}
                                </td>

                                <td>
                                    {{ $student->user->email }}
                                </td>

                                <td>
                                    {{ $student->user->phone }}
                                </td>

                                <td>
                                    {{ $student->college }}
                                </td>

                                <td>
                                    {{ $student->qualification }}
                                </td>

                                <td>

                                    <a  href="{{ route('admin.students.show', $student) }}"
                                    class="btn btn-sm btn-info"
                                    >
                                         <i class="bi bi-eye"></i>
                                    </a>

                                    <a  href="{{ route('admin.students.edit', $student) }}"
                                    class="btn btn-sm btn-warning"
                                    >
                                         <i class="bi bi-pencil"></i>
                                    </a>

                                    <form
                                          action="{{ route('admin.students.destroy', $student) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this student?')"
                                    >

                                          @csrf

                                          @method('DELETE')

                                          <button
                                          type="submit"
                                          class="btn btn-sm btn-danger"
                                          >
                                               <i class="bi bi-trash"></i>
                                          </button>

                                    </form>

                              </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="text-center">
                                    No students found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection
