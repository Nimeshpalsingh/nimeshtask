<!DOCTYPE html>
<html>
<head>
    <title>Import Summary</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; background: #f9f9f9; border-radius: 8px; }
        .header { background: #3b82f6; color: white; padding: 15px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { padding: 20px; background: white; border: 1px solid #ddd; border-top: none; border-radius: 0 0 8px 8px; }
        .stats { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .stat-box { background: #f1f5f9; padding: 15px; border-radius: 5px; text-align: center; flex: 1; margin: 0 5px; }
        .stat-box.success { border-bottom: 3px solid #10b981; }
        .stat-box.error { border-bottom: 3px solid #ef4444; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 14px; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background-color: #f8fafc; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>CSV Import Completed!</h2>
        </div>
        <div class="content">
            <p>Hello Admin,</p>
            <p>The student CSV import process has been completed successfully. Here is the summary:</p>
            
            <div class="stats">
                <div class="stat-box">
                    <strong>Total</strong><br>
                    {{ $importData['total'] }}
                </div>
                <div class="stat-box success">
                    <strong>Success</strong><br>
                    <span style="color:#10b981">{{ $importData['imported'] }}</span>
                </div>
                <div class="stat-box error">
                    <strong>Failed</strong><br>
                    <span style="color:#ef4444">{{ $importData['failed'] }}</span>
                </div>
            </div>

            @if(count($importData['details']) > 0)
                <h3>Details</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(array_slice($importData['details'], 0, 10) as $detail)
                        <tr>
                            <td>{{ $detail['name'] ?? 'N/A' }}</td>
                            <td>
                                @if($detail['status'] == 'Success')
                                    <span style="color:#10b981;">{{ $detail['status'] }}</span>
                                @else
                                    <span style="color:#ef4444;">{{ $detail['status'] }}</span>
                                @endif
                            </td>
                            <td>{{ $detail['reason'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if(count($importData['details']) > 10)
                    <p style="text-align: center; color: #666; font-size: 12px; margin-top: 10px;">
                        *Showing only the first 10 records.
                    </p>
                @endif
            @endif
        </div>
    </div>
</body>
</html>
