/*
 Template Name: Velzon - Admin & Dashboard Template
 Author: Themesbrand
 Website: https://Themesbrand.com/
 Contact: Themesbrand@gmail.com
 File: ecommerce customer Js File
 */


// list js

var checkAll = document.getElementById("checkAll");
if (checkAll) {
    checkAll.onclick = function () {
        var checkboxes = document.querySelectorAll('.form-check-all input[type="checkbox"]');
        var checkedCount = document.querySelectorAll('.form-check-all input[type="checkbox"]:checked').length;
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = this.checked;
            if (checkboxes[i].checked) {
                checkboxes[i].closest("tr").classList.add("table-active");
            } else {
                checkboxes[i].closest("tr").classList.remove("table-active");
            }
        }

        (checkedCount > 0) ? document.getElementById("remove-actions").style.display = 'none' : document.getElementById("remove-actions").style.display = 'block';
    };
}

var perPage = 8;
var editlist = false;

//Table
var options = {
    valueNames: [
        "id",
        "user_name",
        "email",
        "date",
        "status",
    ],
    page: perPage,
    pagination: true,
    plugins: [
        ListPagination({
            left: 2,
            right: 2
        })
    ]
};

// Init list
var customerList = new List("customerList", options).on("updated", function (list) {
    list.matchingItems.length == 0 ?
            (document.getElementsByClassName("noresult")[0].style.display = "block") :
            (document.getElementsByClassName("noresult")[0].style.display = "none");
    var isFirst = list.i == 1;
    var isLast = list.i > list.matchingItems.length - list.page;
    // make the Prev and Nex buttons disabled on first and last pages accordingly
    (document.querySelector(".pagination-prev.disabled")) ? document.querySelector(".pagination-prev.disabled").classList.remove("disabled") : '';
    (document.querySelector(".pagination-next.disabled")) ? document.querySelector(".pagination-next.disabled").classList.remove("disabled") : '';
    if (isFirst) {
        document.querySelector(".pagination-prev").classList.add("disabled");
    }
    if (isLast) {
        document.querySelector(".pagination-next").classList.add("disabled");
    }
    if (list.matchingItems.length <= perPage) {
        document.querySelector(".pagination-wrap").style.display = "none";
    } else {
        document.querySelector(".pagination-wrap").style.display = "flex";
    }

    if (list.matchingItems.length == perPage) {
        document.querySelector(".pagination.listjs-pagination").firstElementChild.children[0].click()
    }

    if (list.matchingItems.length > 0) {
        document.getElementsByClassName("noresult")[0].style.display = "none";
    } else {
        document.getElementsByClassName("noresult")[0].style.display = "block";
    }
});

const xhttp = new XMLHttpRequest();
xhttp.onload = function () {
    var json_records = JSON.parse(this.responseText);
    Array.from(json_records).forEach(raw => {
        customerList.add({
            id: '<a href="javascript:void(0);" class="fw-medium link-primary">#VZ' + raw.id + "</a>",
            user_name: raw.user_name,
            email: raw.email,
            date: raw.date,
            status: isStatus(raw.status)
        });
        customerList.sort('id', {order: "desc"});
        refreshCallbacks();
    });
    customerList.remove("id", '<a href="javascript:void(0);" class="fw-medium link-primary">#VZ2101</a>');
}
//xhttp.open("GET", "build/json/customer-list.json");
//xhttp.send();

isCount = new DOMParser().parseFromString(
        customerList.items.slice(-1)[0]._values.id,
        "text/html"
        );

var isValue = isCount.body.firstElementChild.innerHTML;

var idField = document.getElementById("id-field"),
        customerNameField = document.getElementById("customername-field"),
        emailField = document.getElementById("email-field"),
        dateField = document.getElementById("date-field"),
        statusField = document.getElementById("status-field"),
        addBtn = document.getElementById("add-btn"),
        editBtn = document.getElementById("edit-btn"),
        removeBtns = document.getElementsByClassName("remove-item-btn"),
        editBtns = document.getElementsByClassName("edit-item-btn");
refreshCallbacks();

function filterContact(isValue) {
    var values_status = isValue;
    customerList.filter(function (data) {
        var statusFilter = false;
        matchData = new DOMParser().parseFromString(
                data.values().status,
                "text/html"
                );
        var status = matchData.body.firstElementChild.innerHTML;
        if (status == "All" || values_status == "All") {
            statusFilter = true;
        } else {
            statusFilter = status == values_status;
        }
        return statusFilter;
    });

    customerList.update();
}

function updateList() {
    var values_status = document.querySelector("input[name=status]:checked").value;

    data = userList.filter(function (item) {
        var statusFilter = false;

        if (values_status == "All") {
            statusFilter = true;
        } else {
            statusFilter = item.values().sts == values_status;
        }
        return statusFilter;
    });
    userList.update();
}
;

document.getElementById("showModal").addEventListener("show.bs.modal", function (e) {
    if (e.relatedTarget.classList.contains("edit-item-btn")) {
        document.getElementById("exampleModalLabel").innerHTML = "Edit Customer";
        document.getElementById("showModal").querySelector(".modal-footer").style.display = "block";
        document.getElementById("add-btn").innerHTML = "Update";
    } else if (e.relatedTarget.classList.contains("add-btn")) {
        document.getElementById("exampleModalLabel").innerHTML = "Add Customer";
        document.getElementById("showModal").querySelector(".modal-footer").style.display = "block";
        document.getElementById("add-btn").innerHTML = "Add Customer";
    } else {
        document.getElementById("exampleModalLabel").innerHTML = "List Customer";
        document.getElementById("showModal").querySelector(".modal-footer").style.display = "none";
    }
});
ischeckboxcheck();

document.getElementById("showModal").addEventListener("hidden.bs.modal", function () {
    clearFields();
});

document.querySelector("#curdAllTable").addEventListener("click", function () {
    ischeckboxcheck();
});

var table = document.getElementById("curdAllTable");
// save all tr
var tr = table.getElementsByTagName("tr");
var trlist = table.querySelectorAll(".list tr");

function SearchData() {

    var isstatus = document.getElementById("idStatus").value;
     

    customerList.filter(function (data) {
        matchData = new DOMParser().parseFromString(data.values().status, 'text/html');
        var status = matchData.body.firstElementChild.innerHTML;
        var statusFilter = false;
        //    var dateFilter = false;

        if (status == 'all' || isstatus == 'all') {
            statusFilter = true;
        } else {
            statusFilter = status == isstatus;
        }

        if (statusFilter) {
            return statusFilter
        }
    });
    customerList.update();
}


var count = 11;

var statusVal = new Choices(statusField);

function isStatus(val) {
    switch (val) {
        case "Active":
            return ('<span class="badge bg-success-subtle text-success text-uppercase">' + val + "</span>");
        case "Block":
            return ('<span class="badge bg-danger-subtle text-danger text-uppercase">' + val + "</span>");
    }
}

function ischeckboxcheck() {
    Array.from(document.getElementsByName("chk_child")).forEach(function (x) {
        x.addEventListener("change", function (e) {
            if (x.checked == true) {
                e.target.closest("tr").classList.add("table-active");
            } else {
                e.target.closest("tr").classList.remove("table-active");
            }

            var checkedCount = document.querySelectorAll('[name="chk_child"]:checked').length;
            if (e.target.closest("tr").classList.contains("table-active")) {
                (checkedCount > 0) ? document.getElementById("remove-actions").style.display = 'block' : document.getElementById("remove-actions").style.display = 'none';
            } else {
                (checkedCount > 0) ? document.getElementById("remove-actions").style.display = 'block' : document.getElementById("remove-actions").style.display = 'none';
            }
        });
    });
}

function refreshCallbacks() {
 
}

function clearFields() {
    customerNameField.value = "";
    emailField.value = "";
    dateField.value = "";
}

document.querySelector(".pagination-next").addEventListener("click", function () {
    (document.querySelector(".pagination.listjs-pagination")) ? (document.querySelector(".pagination.listjs-pagination").querySelector(".active")) ?
            document.querySelector(".pagination.listjs-pagination").querySelector(".active").nextElementSibling.children[0].click() : '' : '';
});
document.querySelector(".pagination-prev").addEventListener("click", function () {
    (document.querySelector(".pagination.listjs-pagination")) ? (document.querySelector(".pagination.listjs-pagination").querySelector(".active")) ?
            document.querySelector(".pagination.listjs-pagination").querySelector(".active").previousSibling.children[0].click() : '' : '';
});
