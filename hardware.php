<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/hardware.css">
    <title>Register</title>
    <script>
        function redirectToHomePage() {
            window.location.href = 'index.php';
        }
    </script>
    <style>
        .autocomplete-items {
            position: absolute;
            color: black;
            border: 1px solid #d4d4d4;
            border-bottom: none;
            border-top: none;
            left: 0;
            right: 0;
        }
        #cpu:focus + .autocomplete-items {
            top: 25%;
        }
        #mobo:focus + .autocomplete-items {
            top: 45%;
        }
        #gpu:focus + .autocomplete-items {
            top: 65%;
        }
        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
            background-color: #fff;
            border-bottom: 1px solid #d4d4d4;
        }

        .autocomplete-items div:hover {
            background-color: #e9e9e9;
        }

        .autocomplete-active {
            background-color: DodgerBlue !important;
            color: #ffffff;
        }
    </style>
</head>
<body>
<div class="page-transition">
    <div class="col-md-6 center-container">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6"><br>
                    <h2 class="textik text-center mb-4">Váš Hardware</h2>
                    <form id="hardware-form" autocomplete="off" action="hardware_process.php" method="post">
                        <div class="form-group">
                            <label for="cpu">Procesor:</label>
                            <input type="text" class="form-control" id="cpu" name="cpu" required>
                        </div>
                        <div class="form-group">
                            <label for="mobo">Operační systém:</label>
                            <input type="text" class="form-control" id="mobo" name="mobo" required>
                        </div>
                        <div class="form-group">
                            <label for="gpu">Grafická karta:</label>
                            <input type="text" class="form-control" id="gpu" name="gpu" required>
                        </div>
                        <div class="form-group">
                            <label for="ram">RAM[Počet GB]</label>
                            <input type="number" class="form-control" id="ram" name="ram" min="1" max="256" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block submit">Pokračovat</button>
                    </form>
                </div>
            </div>
            <div class="text-center button-group">
                <label for="account">Chcete vyplnit později?</label>
                <button type="button" class="btn btn-primary btn-sm back-home-btn" onclick="redirectToHomePage()">Redirect</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function() {
    function initAutocompleteForInput(inputElement, url) {
        inputElement.on("input", function() {
            var val = $(this).val();
            if (!val) { return false; }
            loadOptions(url, val, function(options) {
                autocomplete(inputElement, options);
            });
        });
    }

    function loadOptions(url, input, callback) {
        $.ajax({
            url: url,
            method: 'GET',
            data: { input: input },
            success: function(data) {
                var options = JSON.parse(data);
                callback(options);
            },
            error: function(xhr, status, error) {
                console.error("Chyba při načítání možností: " + error);
            }
        });
    }
    $('#hardware-form').on('submit', function(e) {
    e.preventDefault();
    
    var cpu = $('#cpu').val();
    var gpu = $('#gpu').val();
    var mobo = $('#mobo').val();
    var moboOptions = ['Windows XP', 'Windows Vista', 'Windows 7', 'Windows 7 (64-Bit)', 'Windows 8', 'Windows 8.1', 'Windows 10', 'Windows 11'];

    loadOptions('get_processors.php', cpu, function(cpuOptions) {
        loadOptions('get_graphics_cards.php', gpu, function(gpuOptions) {
            if (cpuOptions.indexOf(cpu) === -1 || gpuOptions.indexOf(gpu) === -1 || moboOptions.indexOf(mobo) === -1) {
                alert('Prosím, vyberte platný procesor, grafickou kartu a operační systém.');
            } else {
                $('#hardware-form').unbind('submit').submit();
            }
        });
    });
});

    function initAutocomplete() {
        initAutocompleteForInput($('#cpu'), 'get_processors.php');
        initAutocompleteForInput($('#gpu'), 'get_graphics_cards.php');

        autocomplete($('#mobo'), ['Windows XP', 'Windows Vista', 'Windows 7', 'Windows 7 (64-Bit)', 'Windows 8', 'Windows 8.1', 'Windows 10', 'Windows 11']);
    }

    function autocomplete(inp, arr) {
        var currentFocus;
        inp.on("input", function() {
            var val = $(this).val();
            closeAllLists();
            if (!val) { return false; }
            currentFocus = -1;
            var list = $('<div>').addClass('autocomplete-items');
            $(this).parent().append(list);

            var counter = 0;
            for (var i = 0; i < arr.length; i++) {
                if (counter >= 10) break;
                if (arr[i].toUpperCase().indexOf(val.toUpperCase()) > -1) {
                    var item = $('<div>').html(arr[i]);
                    item.on("click", function() {
                        inp.val($(this).text());
                        closeAllLists();
                    });
                    list.append(item);
                    counter++;
                }
            }
        });

        inp.on("keydown", function(e) {
            var x = $(this).siblings('.autocomplete-items');
            if (x.length) x = x.find('div');
            if (e.keyCode == 40) {
                currentFocus++;
                addActive(x);
            } else if (e.keyCode == 38) {
                currentFocus--;
                addActive(x);
            } else if (e.keyCode == 13) {
                e.preventDefault();
                if (currentFocus > -1) {
                    if (x) x.eq(currentFocus).click();
                }
            }
        });

        function addActive(x) {
            if (!x) return false;
            removeActive(x);
            if (currentFocus >= x.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = (x.length - 1);
            x.removeClass("autocomplete-active");
            x.eq(currentFocus).addClass("autocomplete-active");
        }

        function removeActive(x) {
            x.removeClass("autocomplete-active");
        }

        function closeAllLists() {
            $('.autocomplete-items').remove();
        }

        $(document).on("click", function(e) {
            if (!$(e.target).closest('.autocomplete').length) {
                closeAllLists();
            }
        });
    }
    initAutocomplete();
});
</script>

</body>
</html>
