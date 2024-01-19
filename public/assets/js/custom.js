$(document).on("keypress", ".numericonly", function (e) {
    e = e ? e : window.event;
    var charCode = e.which ? e.which : e.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        e.preventDefault();
    } else {
        return true;
    }
});

$(document).on("keypress", ".numeric-dot-only", function (e) {
    e = e ? e : window.event;
    var charCode = e.which ? e.which : e.keyCode;
    if (charCode == 46) {
        return true;
    } else if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        e.preventDefault();
    } else {
        return true;
    }
});

$(document).on("click", ".modal-link", function (e) {
    e.preventDefault();
    var URL = $(this).attr("href");
    var title = $(this).data("title");
    $("#common-modal .modal-title").text(title);
    $.ajax({
        url: URL,
        cache: false,
        beforeSend: function () {
            $("#lds-roller").show();
        },
        success: function (res) {
            $("#common-modal").modal("show");
            $(".modal-body").html(res);
            $("#lds-roller").hide();
        },
        error: function (request, status, error) {
            if (request.status == "500") {
                toastrerror(error);
                $("#lds-roller").hide();
            }
        },
    });
});

async function ajaxDynamicMethod(url, method, formData = "") {
    $("#lds-roller").show();
    const response = await $.ajax({
        type: method,
        url: url,
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (data) {
            $(".errors").text("");
            if (data.error) {
                if (data.errors.length == "0" && data.msg) {
                    toastrerror(data.msg);
                } else {
                    for (const [key, value] of Object.entries(data.errors)) {
                        console.log("#" + key + "error");
                        $("#" + key + "error").text(value);
                    }
                }
            } else if (data.success && data.route) {
                window.location.href = data.route;
            }
        },
        error: function (request, status, error) {
            if (request.status == "500" || request.status == "405") {
                toastrerror(error);
                $("#lds-roller").hide();
            }
        },
    });
    $("#lds-roller").hide();
    return response;
}

function checkMultipleDetails(form) {
    var frequencyMap = {};
    $.each(form, function (index, item) {
        var name = item.name;
        frequencyMap[name] = (frequencyMap[name] || 0) + 1;
    });

    // Extract names with frequency greater than 1
    var repeatedNames = [];
    for (var name in frequencyMap) {
        if (frequencyMap.hasOwnProperty(name) && frequencyMap[name] > 1) {
            repeatedNames.push(name);
        }
    }

    return repeatedNames;
}

function generateFormData(param) {
    var dataArr = $(param).serializeArray();
    var mutipleValue = checkMultipleDetails(dataArr);
    var formData = new FormData();
    $.each(dataArr, function (i, field) {
        if ($.inArray(field.name, mutipleValue) !== -1) {
        } else {
            formData.append(field.name, field.value);
        }
    });
    $.each(mutipleValue, function (index, value) {
        $.each(dataArr, function (i, field) {
            if (field.name == value) {
                formData.append(
                    field.name.replace("[]", "") + "[]",
                    field.value
                );
            }
        });
    });
    $.each(
        $(param).find('input[type="file"]:not([multiple])'),
        function (i, field) {
            formData.append(
                field.name,
                $(field)[0].files[0] != undefined ? $(field)[0].files[0] : ""
            );
        }
    );
    $.each($(param).find('input[type="file"][multiple]'), function (i, field) {
        var fileInput = $(field)[0];
        var files = fileInput.files;
        for (var i = 0; i < files.length; i++) {
            formData.append(field.name.replace("[]", "") + "[]", files[i]);
        }
        // formData.append(field.name + "[]", (($(field)[0].files[0]) != undefined) ? $(field)[0].files[0] : "");
    });

    return formData;
}

window.onpageshow = function (event) {
    if (event.persisted) {
        location.reload();
    }
};

$(document).on("click", "#common-modal.modal .close", function () {
    $("#common-modal").modal("hide");
});

$(document).on("click", ".add-to-cart", async function (e) {
    e.preventDefault();
    var data = await ajaxDynamicMethod(
        $(this).data("url"),
        "GET"
    );
    if (data.success) {
        toastrsuccess(data.msg);
        $(".cart-count").text(data.data.cart_count);
    }
});
