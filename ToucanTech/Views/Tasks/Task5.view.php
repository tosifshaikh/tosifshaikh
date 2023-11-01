<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task 5</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .error {
            border-color: brown;
        }

        .success {
            border-color: darkgreen;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

    <script>
        function Task(params) {
            let __ = this,
                b = params.body,
                ib = params.inputbody,
                headers = ['#', 'Name', 'Email', 'School'],
                gridTable = '',
                gridData = [],
                t = params.target,
                dt = params.data;
            __.init = () => {
                __.attachEvents();
                __.renderGrid(0);
            }
            __.attachEvents = () => {
                $('.btnSave', b).on('click', __.save);
                $("input", ib).on("focusout", __.focusout);
                $("#exportcsv", b).on("click", __.exportCSV);
                $("#report", b).on("click", __.report);
                $("#grid", b).on("click", __.grid);
            }
            __.grid = () => {
                __.renderGrid(1);
            }
            __.exportCSV = () => {
                $(b).append('<form method="POST" action="?Task5" id="exportCSV"><input type="hidden" name="exportcsv" value="exportcsv"></form>');
                $('#exportCSV').submit().remove();
                __.grid();
            }
            __.report = () => {
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: 'index.php?Task5/report',
                    success: function(res) {
                        if (res.length == 0) {
                            return;
                        }
                        $(t).html('<canvas id="myChart" style="width:100%;max-width:600px"></canvas>')
                        let xValues = [];
                        let yValues = [];
                        let barColors = [];
                        res.forEach((item) => {
                            xValues.push(item.school_name);
                            yValues.push(item.total_members);
                            barColors.push("#" + Math.floor(Math.random() * 16777215).toString(16));
                        });
                        new Chart("myChart", {
                            type: "bar",
                            data: {
                                labels: xValues,
                                datasets: [{
                                    backgroundColor: barColors,
                                    data: yValues
                                }]
                            },
                            options: {
                                legend: {
                                    display: false
                                },
                                title: {
                                    display: true,
                                    text: "Number of memebers in school"
                                }
                            }
                        });
                    }
                });

            }
            __.focusout = () => {
                $('[name]', ib).each(function() {
                    let v = $(this);
                    if (!(new RegExp(v.attr('validate-mask'))).test(v.val())) {
                        v.addClass('error');
                        v.removeClass('success');

                    } else {
                        v.addClass('success');
                        v.removeClass('error');
                    }
                })
            }
            __.save = () => {
                let data = {};
                $('[name]', ib).each(function() {
                    let v = $(this);
                    v.blur();
                    if (!__.validate(v)) {
                        return false;
                    }
                    data[v.attr('name')] = v.val();
                });
                if (Object.keys(data).length == 0) {
                    return false;
                }
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                   // url: 'Task5.php?save',
                   url: 'index.php?Task5/save',
                    data: {
                        data: data
                    },
                    success: function(res) {
                        if (res.success) {
                            alert('Data Added Successfully.');
                            $('input,select', ib).val('').removeClass('success');
                            __.renderGrid(1);
                        } else {
                            alert('Some Error Occured.');
                        }
                    }
                });

            }
            __.renderTable = () => {
                gridTable = '<table width="50%" class="table table-striped table-hover">';
                gridTable += __.getHeader();
                gridTable += __.getRows();
                gridTable += '</table>';
                $(t).html(gridTable);
            }
            __.renderGrid = (flag) => {
                if(flag == 0) {
                    gridData = dt;
                    __.renderTable();
                }  else {
                    $.ajax({
                    type: 'post',
                    dataType: 'json',
                   // url: 'Task5.php?grid',
                    url: 'index.php?Task5/grid',
                    success: function(res) {
                        gridData = res;
                        __.renderTable();
                    }
                });
                }
               
            }
            __.getHeader = () => {
                let tr = '';
                headers.forEach((item) => {
                    tr += '<th>' + item + '</th>';
                })
                return tr;
            }
            __.getRows = () => {
                let tr = '';
                gridData.forEach((item) => {
                    tr += `<tr>`;
                    tr += `<td>${item.member_id}</td>`;
                    tr += `<td>${item.name}</td>`;
                    tr += `<td>${item.email_address}</td>`;
                    tr += `<td>${item.school_name}</td>`;
                    tr += `</tr>`;
                });
                return tr;
            }
            __.validate = (ele) => {
                if (ele.hasClass('error')) {
                    return false;
                }
                return true;
            }
            return {
                run: __.init
            }
        }
        $(function() {
             new Task({
                body: $('.container'),
                inputbody: $('.input-body'),
                target: '#divGrid',
                data : <?= $data ?>
            }).run();
        });
    </script>
</head>

<body>
    <div class="container">
        <h6 class="text-center">Member Details</h6>
        <div class="input-body">
            <div class="row">
                <div class="col-md-3">
                    <label for="" class="form-label">Name</label>
                    <input type="text" class="form-control form-control-sm" name="name" placeholder="Name" validate-mask="^[a-zA-Z0-9_ ]*$">
                </div>
                <div class="col-md-3">
                    <label for="" class="form-label">Email</label>
                    <input type="text" class="form-control form-control-sm" name="email_address" placeholder="abc@email.com" validate-mask="\S+@\S+\.\S+$">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="" class="form-label">School</label>
                    <select class="form-select form-select-sm" name="school_id" validate-mask="^\d+$">
                        <option value="" selected>Select School</option>
                        <?php foreach ($school as $item) :
                            echo "<option value='" . $item['school_id'] . "'>" . $item['school_name'] . "</option>";
                        endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-primary btn-sm btnSave">Add</button>
        <div id="headers" class="mt-3">
            <input type="button" class="btn btn-sm btn-secondary" value="Export to CSV" id="exportcsv">
            <input type="button" class="btn btn-sm btn-secondary"  value="Report" id="report">
            <input type="button" class="btn btn-sm btn-secondary"  value="Grid" id="grid">
        </div>
        <div id="divGrid" class="mt-3">
                      
        </div>

    </div>

</body>

</html>