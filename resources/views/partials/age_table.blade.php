@if(count($ages)>0)
    <?php $age = (object)head($ages);

    ?>
    <h2>{{ $ageState }} Aging Data as of {{ date("m/d/Y", strtotime($age->as_of)) }}</h2>
    Starting:  {{ date("m/d/Y", strtotime($age->start_week)) }}
    Ending  {{ date("m/d/Y", strtotime($age->end_week)) }}
    <table class="table table-striped table-bordered ">
        <thead>
        <tr>
            <th>Age Group</th>
            <th>Covid Deaths</th>
            <th>Percent of all Covid Deaths</th>
            <th>Pneumonia Deaths</th>
            <th>Pneumonia Covid Deaths</th>
            <th>Flu Deaths</th>
            <th>Pneumonia Flu Covid</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($ages as $ageGroup => $row)
            <?php $row = (object)$row;?>
            <tr>
                <td>{{ $ageGroup }}</td>
                <td>{{ $row->covid_deaths }}</td>
                <td>{{ $row->percent_covid_deaths }}%</td>
                <td>{{ $row->pneumonia_deaths }}</td>
                <td>{{ $row->pneumonia_covid_deaths }}</td>
                <td>{{ $row->flu_deaths }}</td>
                <td>{{ $row->pneumonia_flu_covid_deaths }}</td>
                <td>{{ $row->total_deaths }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
