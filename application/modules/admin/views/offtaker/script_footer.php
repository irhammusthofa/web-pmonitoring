<script>
var table;
$(document).ready(function() {
    $('.select2').select2();
    loadtable();
});

function loadtable() {
    //datatables
    var id_shipper = $("#perusahaan").val();
    var tanggal = $("#tanggal").val();

    table = $('#dtable').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': true,
        'info': true,
        'autoWidth': true,
        'columns': [{
                'width': '50px'
            },
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
        ],
        'bDestroy': true,
        'processing': true, //Feature control the processing indicator.\
        'serverSide': true, //Feature control DataTables' server-side processing mode.\
        'order': [], //Initial no order.

        // Load data for the table's content from an Ajax source
        'ajax': {
            'url': "<?= site_url('admin/offtaker/ajax_list/') ?>",
            'type': "POST",
            'data':{
                id_shipper:id_shipper,
                tgl:tanggal
            },
        },

        //Set column definition initialisation properties.
        'columnDefs': [{
            'targets': [0], //first column / numbering column
            'orderable': false, //set not orderable
        }, ],

    });
}

</script>