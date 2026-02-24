<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        @page {
            margin: 0.5cm;
            size: a4 landscape;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #000;
            line-height: 1.1;
            font-size: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header p {
            margin: 2px 0;
            font-size: 12px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 0.5pt solid #000;
            padding: 2px;
            height: 80px;
            vertical-align: middle;
            text-align: center;
            overflow: hidden;
        }

        .period-header th {
            height: 40px;
            font-size: 11px;
            background-color: #f3f4f6;
        }

        .period-time {
            font-size: 8px;
            font-weight: normal;
            display: block;
            margin-top: 2px;
            color: #444;
        }

        .day-label {
            width: 60px;
            font-size: 24px;
            font-weight: bold;
            background-color: #f3f4f6;
        }

        .item-container {
            margin-bottom: 4px;
            border-bottom: 0.2pt solid #eee;
            padding-bottom: 2px;
        }

        .item-container:last-child {
            margin-bottom: 0;
            border-bottom: none;
            padding-bottom: 0;
        }

        .subject-name {
            display: block;
            font-size: 10px;
            font-weight: bold;
            color: #000;
        }

        .class-name {
            font-size: 9px;
            color: #444;
        }

        .teacher-name {
            font-size: 8px;
            color: #777;
            font-style: italic;
        }

        .room-name {
            font-size: 9px;
            font-weight: bold;
            color: #059669;
            /* emerald-600 */
        }

        .footer {
            margin-top: 10px;
            font-size: 8px;
            border-top: 0.5pt solid #eee;
            padding-top: 5px;
        }

        .footer-left {
            float: left;
        }

        .footer-right {
            float: right;
        }

        .clear {
            clear: both;
        }

        .break-cell {
            background-color: #f9fafb;
        }

        .break-text {
            font-size: 10px;
            font-weight: bold;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 2px;
            writing-mode: tb-rl;
            transform: rotate(90deg);
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $title ?? 'Academic Schedule' }}</h1>
        <p>Academic Year: {{ $activeYear->name ?? '-' }} ({{ $activeYear->semester ?? '-' }})</p>
    </div>

    @php
        $dayLabels = [
            1 => 'Sen',
            2 => 'Sel',
            3 => 'Ra',
            4 => 'Ka',
            5 => 'Ju',
            6 => 'Sab'
        ];
    @endphp

    <table>
        <thead>
            <tr class="period-header">
                <th style="width: 60px;"></th>
                @php $p = 1; @endphp
                @foreach($headerSlots as $slot)
                    <th>
                        @if(!$slot->is_break)
                            <div style="font-weight: bold; font-size: 14px;">{{ $p++ }}</div>
                        @endif
                        <span class="period-time">{{ substr($slot->start_time, 0, 5) }} -
                            {{ substr($slot->end_time, 0, 5) }}</span>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($calendarData as $dayNum => $blocks)
                <tr>
                    <td class="day-label">
                        {{ $dayLabels[$dayNum] ?? '??' }}
                    </td>
                    @foreach($blocks as $block)
                        <td colspan="{{ $block['span'] }}" class="{{ $block['type'] === 'break' ? 'break-cell' : '' }}">
                            @if($block['type'] === 'subject')
                                @foreach($block['items'] as $item)
                                    <div class="item-container">
                                        <span class="subject-name">{{ $item->subject->name }}</span>
                                        <div style="margin-top: 2px;">
                                            <span class="class-name">{{ $item->academicClass->name }}</span>
                                            <span style="color: #ccc; margin: 0 2px;">|</span>
                                            <span
                                                class="room-name">{{ preg_replace('/[^a-zA-Z0-9 ]/', '', $item->room->name) ?: $item->room->name }}</span>
                                        </div>
                                        <div class="teacher-name">{{ $item->teacher->name }}</div>
                                    </div>
                                @endforeach
                            @elseif($block['type'] === 'break')
                                <div class="break-text">{{ $block['name'] }}</div>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headerSlots) + 1 }}" style="height: 100px; color: #999;">
                        No schedule data available for the selected filters.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="footer-left">Generated on: {{ date('d/m/Y H:i') }}</div>
        <div class="footer-right">Siakad - Academic Information System</div>
        <div class="clear"></div>
    </div>
</body>

</html>