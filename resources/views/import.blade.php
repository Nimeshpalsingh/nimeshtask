<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student CSV Import</title>
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
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding-top: 80px;
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

        .card {
            background: #ffffff;
            width: 100%;
            max-width: 500px;
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-weight: 600;
        }

        .upload-section {
            display: block;
        }

        .file-input {
            width: 100%;
            padding: 15px;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            margin-bottom: 25px;
            color: #64748b;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-input:hover {
            border-color: #3b82f6;
            background: #f8fafc;
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
        }

        .btn:hover {
            background: #2563eb;
        }
        
        .btn:active {
            transform: scale(0.98);
        }

        .btn:disabled {
            background: #94a3b8;
            cursor: not-allowed;
        }

        /* Progress Section */
        .progress-section {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: #f8fafc;
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            border: 1px solid #e2e8f0;
        }

        .stat-title {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
        }

        .stat-value.success { color: #10b981; }
        .stat-value.error { color: #ef4444; }

        .progress-container {
            width: 100%;
            height: 24px;
            background: #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            margin-bottom: 10px;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #3b82f6, #60a5fa);
            width: 0%;
            transition: width 0.4s ease;
            position: relative;
        }

        .progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                45deg,
                rgba(255, 255, 255, 0.2) 25%,
                transparent 25%,
                transparent 50%,
                rgba(255, 255, 255, 0.2) 50%,
                rgba(255, 255, 255, 0.2) 75%,
                transparent 75%,
                transparent
            );
            background-size: 1rem 1rem;
            animation: stripe 1s linear infinite;
        }

        .progress-text {
            text-align: center;
            font-weight: 600;
            color: #3b82f6;
            margin-bottom: 20px;
            font-size: 18px;
        }

        .completed-message {
            display: none;
            text-align: center;
            color: #10b981;
            font-size: 20px;
            font-weight: 600;
            padding: 15px;
            background: #d1fae5;
            border-radius: 12px;
            animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .info {
            margin-top: 25px;
            color: #64748b;
            font-size: 14px;
            text-align: center;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
        }

        @keyframes stripe {
            0% { background-position: 1rem 0; }
            100% { background-position: 0 0; }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes popIn {
            0% { opacity: 0; transform: scale(0.8); }
            100% { opacity: 1; transform: scale(1); }
        }
        
        .back-btn {
            display: none;
            margin-top: 15px;
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            background: white;
            color: #475569;
            border-radius: 12px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .back-btn:hover {
            background: #f8fafc;
            color: #0f172a;
        }

        .details-table-container {
            margin-top: 25px;
            max-height: 250px;
            overflow-y: auto;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .details-table th, .details-table td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .details-table th {
            background: #f8fafc;
            color: #64748b;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .details-table tr:last-child td {
            border-bottom: none;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-success { background: #d1fae5; color: #10b981; }
        .badge-failed { background: #fee2e2; color: #ef4444; }
        .badge-skipped { background: #fef3c7; color: #d97706; }

        .toast {
            visibility: hidden;
            min-width: 250px;
            background-color: #10b981;
            color: #fff;
            text-align: center;
            border-radius: 8px;
            padding: 16px;
            position: fixed;
            z-index: 1000;
            right: 30px;
            bottom: 30px;
            font-size: 15px;
            font-weight: 600;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);
            opacity: 0;
            transition: opacity 0.5s, bottom 0.5s, visibility 0.5s;
        }

        .toast.show {
            visibility: visible;
            opacity: 1;
            bottom: 50px;
        }

    </style>
</head>
<body>

    <nav class="navbar">
        <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Task 1 (CSV Import)</a>
        <a href="/task2" class="{{ request()->is('task2') ? 'active' : '' }}">Task 2 (Many to Many)</a>
    </nav>

    <div class="card">
        <h2>📁 Student CSV Upload</h2>

        <!-- Upload Section -->
        <div class="upload-section" id="uploadSection">
            <form id="importForm">
                @csrf
                <input type="file" name="csv" id="csvFile" class="file-input" accept=".csv,.txt" required>
                <button type="submit" class="btn" id="submitBtn">
                    Import Students
                </button>
            </form>
            
            <div class="info">
                CSV Format required:<br>
                <strong>name, class, phone</strong>
            </div>
        </div>

        <!-- Progress Section -->
        <div class="progress-section" id="progressSection">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-title">Total Records</div>
                    <div class="stat-value" id="statTotal">0</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Imported</div>
                    <div class="stat-value success" id="statImported">0</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Failed</div>
                    <div class="stat-value error" id="statFailed">0</div>
                </div>
            </div>

            <div class="progress-container">
                <div class="progress-bar" id="progressBar"></div>
            </div>
            <div class="progress-text" id="progressText">0%</div>

            <div class="completed-message" id="completedMessage">
                Import Completed ✅
            </div>

            <div class="details-table-container" id="detailsTableContainer">
                <table class="details-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody id="detailsTableBody">
                    </tbody>
                </table>
            </div>
            
            <button class="back-btn" id="backBtn" onclick="location.reload()">Upload Another File</button>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast">✅ Success! Alert sent on Email.</div>

    <script>
        document.getElementById('importForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const fileInput = document.getElementById('csvFile');
            if (!fileInput.files.length) return;

            const formData = new FormData(this);
            const submitBtn = document.getElementById('submitBtn');
            
            // Switch UI
            document.getElementById('uploadSection').style.display = 'none';
            document.getElementById('progressSection').style.display = 'block';
            
            // Start fake progress
            let progress = 0;
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');
            
            const progressInterval = setInterval(() => {
                if (progress < 90) {
                    progress += Math.random() * 15;
                    if (progress > 90) progress = 90;
                    progressBar.style.width = `${progress}%`;
                    progressText.textContent = `${Math.floor(progress)}%`;
                }
            }, 300);

            try {
                const response = await fetch('/import', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();
                clearInterval(progressInterval);

                if (data.success) {
                    // Calculate success percentage
                    let finalPercentage = 100;
                    if (data.total > 0) {
                        finalPercentage = Math.round((data.imported / data.total) * 100);
                    }
                    
                    // Update stats
                    document.getElementById('statTotal').textContent = data.total;
                    
                    // Animate counts for visual effect
                    animateValue("statImported", 0, data.imported, 1000);
                    animateValue("statFailed", 0, data.failed, 1000);

                    // Complete progress bar
                    progressBar.style.width = '100%';
                    progressBar.style.background = 'linear-gradient(90deg, #10b981, #34d399)';
                    progressBar.style.animation = 'none'; // stop stripe animation
                    
                    // In UI they requested progress as "██████████ 97%"
                    progressText.innerHTML = `<span style="color:#10b981">${finalPercentage}% Success Rate</span>`;
                    
                    // Populate Details Table
                    const tbody = document.getElementById('detailsTableBody');
                    tbody.innerHTML = '';
                    
                    if (data.details && data.details.length > 0) {
                        data.details.forEach(item => {
                            let badgeClass = '';
                            if (item.status === 'Success') badgeClass = 'badge-success';
                            else if (item.status === 'Failed') badgeClass = 'badge-failed';
                            else if (item.status === 'Skipped') badgeClass = 'badge-skipped';

                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td>${item.name}</td>
                                <td>${item.phone}</td>
                                <td><span class="badge ${badgeClass}">${item.status}</span></td>
                                <td style="color:#64748b">${item.reason}</td>
                            `;
                            tbody.appendChild(tr);
                        });
                    }

                    setTimeout(() => {
                        document.getElementById('completedMessage').style.display = 'block';
                        document.getElementById('detailsTableContainer').style.display = 'block';
                        document.getElementById('backBtn').style.display = 'block';
                        
                        // Show Toast Notification
                        const toast = document.getElementById('toast');
                        toast.classList.add('show');
                        setTimeout(() => toast.classList.remove('show'), 4000);
                    }, 1000);
                } else {
                    alert('Error processing file.');
                    location.reload();
                }

            } catch (error) {
                clearInterval(progressInterval);
                alert('An error occurred during upload.');
                console.error(error);
                location.reload();
            }
        });

        function animateValue(id, start, end, duration) {
            if (start === end) {
                document.getElementById(id).textContent = end;
                return;
            }
            const obj = document.getElementById(id);
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                obj.innerHTML = Math.floor(progress * (end - start) + start);
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }
    </script>
</body>
</html>