<h2>{{ $label }} Data</h2>
<div style="text-align:center;font-size: 10px">(click on the table headers to change sorting options)</div>

<table class="table table-striped table-bordered sortableTable" style="width:900px">
    <thead>
    <tr>
        <th>{{ $label }}</th>
        <th>Population</th>
        <th>Cases</th>
        <th>% Cases</th>
        <th>Deaths</th>
        <th>% Deaths</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($rows as $row)
        <tr>
            <td>
                @if($row->link !== null)
                    <a href="{{ $row->link }}">{{ $row->label }}</a>
                @else
                    {{ $row->label }}
                @endif
            </td>
            <td>{{ number_format( $row->population, 0) }}</td>
            <td>{{ number_format( $row->cases, 0) }}</td>
            <td>{{ $row->percent_cases }}%</td>
            <td>{{ number_format( $row->deaths, 0) }}</td>
            <td>{{ $row->percent_deaths }}%</td>
            <td>
                @if($row->drilldown !== null)
                    <a href="{{ $row->drilldown }}">Drilldown</a>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
