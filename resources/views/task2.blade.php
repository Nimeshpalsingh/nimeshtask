<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task 2: Many to Many Relationship</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding-top: 100px;
            padding-bottom: 50px;
        }

        .navbar {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 10px 20px;
            border-radius: 30px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            display: flex;
            gap: 15px;
            z-index: 100;
        }
        .navbar a {
            text-decoration: none;
            color: #64748b;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 20px;
            transition: all 0.3s;
        }
        .navbar a:hover {
            background: #f1f5f9;
        }
        .navbar a.active {
            background: #3b82f6;
            color: white;
        }

        .container {
            display: flex;
            gap: 30px;
            width: 90%;
            max-width: 1200px;
        }

        .card {
            background: #ffffff;
            flex: 1;
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #475569;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 15px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            background: #3b82f6;
            color: white;
            border-radius: 12px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: background 0.3s ease, transform 0.1s ease;
            margin-top: 10px;
        }

        .btn:hover {
            background: #2563eb;
        }
        
        .btn:active {
            transform: scale(0.98);
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #10b981;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            background: #f8fafc;
            color: #64748b;
            font-weight: 600;
        }

        .badge {
            display: inline-block;
            background: #e0f2fe;
            color: #0284c7;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin: 2px;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Task 1 (CSV Import)</a>
        <a href="/task2" class="{{ request()->is('task2') ? 'active' : '' }}">Task 2 (Many to Many)</a>
    </nav>

    <div class="container">
        <!-- Form Section -->
        <div class="card">
            <h2>➕ Add Student & Course</h2>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('task2.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Student Name</label>
                    <input type="text" name="student_name" class="form-control" required placeholder="e.g. John Doe">
                </div>
                <div class="form-group">
                    <label>Student Class</label>
                    <input type="text" name="student_class" class="form-control" required placeholder="e.g. 10th Grade">
                </div>
                <div class="form-group">
                    <label>Student Phone</label>
                    <input type="text" name="student_phone" class="form-control" required placeholder="e.g. 1234567890">
                </div>
                <div class="form-group">
                    <label>Course Name</label>
                    <input type="text" name="course_name" class="form-control" required placeholder="e.g. Mathematics">
                </div>
                
                <button type="submit" class="btn">Save & Link Data</button>
            </form>
        </div>

        <!-- Data Section -->
        <div class="card">
            <h2>📋 Retrieved Data</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Phone</th>
                            <th>Enrolled Courses</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->class }}</td>
                                <td>{{ $student->phone }}</td>
                                <td>
                                    @forelse($student->courses as $course)
                                        <span class="badge">{{ $course->name }}</span>
                                    @empty
                                        <span style="color:#94a3b8; font-size:13px;">No courses</span>
                                    @endforelse
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: #64748b;">No data found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
