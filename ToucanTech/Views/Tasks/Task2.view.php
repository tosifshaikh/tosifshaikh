<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <title>Task 2</title>
    <style>
        .container {
            width: 80%;
        }

        .textAlign {
            text-align: center;
        }

        .end-align {
            text-align: end;
        }

        #divGrid {
            width: 80%;
            margin: auto;
            margin-top: 30px
        }
    </style>
</head>
<script>
    function Task(target, data) {
        let __ = this,
            headers = ['First name', 'Last name', 'Email address'];
            gridTable = '',
            gridData = [],
            filterData = {},
            dt = <?= $data ?>;
        __.init = () => {
            __.attachEvent();
            __.loadGrid(0);
        }
        __.attachEvent = () => {
            $('#btnSearch').on('click', __.search);
        }
        __.loadGrid = (flag) => {
            let params = {
                'get-grid': 1
            };
            if (Object.keys(filterData).length > 0) {
                params['q'] = filterData;
            }
            if(flag == 0) {
                gridData = dt;
                __.renderTable();
            } else {
                    $.ajax({
                    type: 'POST',
                    data: params,
                    dataType: 'json',
                    url: 'index.php?Task2',
                    success: function(res) {
                        gridData = res;
                        __.renderTable();
                    }
                });
            }
            
        }
        __.search = (e) => {
            filterData = {};
            let searchVal = $('#search').val();
            if (searchVal != '') {
                filterData['search'] = searchVal;
                __.loadGrid(1);
            }
        }
        __.renderTable = () => {
            gridTable = '<table width="100%" class="textAlign">';
            gridTable += __.getHeader();
            gridTable += __.getRows();
            gridTable += '</table>';
            $(target).html(gridTable);
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
                tr += `<td>${item.Firstname}</td>`;
                tr += `<td>${item.Surname}</td>`;
                tr += `<td>${item.emailaddress}</td>`;
                tr += `</tr>`;
            });
            return tr;
        }
        return {
            run: __.init
        }
    }

    function load() {
        new Task('#divGrid').run();
    }
</script>

<body onload="load()">
    <div class="container">
        <h2 class="textAlign">Task 2</h2>
        <div class="end-align"><input type="text" name="search" id="search">
            <button id="btnSearch">Search</button>
        </div>
    </div>

    <div id="divGrid">

    </div>
</body>

</html>