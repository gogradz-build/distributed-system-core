<template>
  <div class="container mt-4" style="height: 100%;">
    <div class="row">
      <div class="invoice p-3 mb-3">
        <!-- title row -->
        <div class="row">
          <div class="col-6">
            <img src="/images/logo.png" alt="" width="100%" height="auto">
            <address style="font-size: 18px !important; margin-left: 30px; margin-bottom: 10px;">
              Diyakalamulla ,
              Kuliyapitiya
             ( 0769927407)
            </address>
          </div>
          <div class="col-6  d-flex justify-content-center mt-4">
            <h2>
              <small class="float-right">Date: {{ new Date(sale.created_at).toLocaleDateString() }}</small>
            </h2>
          </div>
          <!-- /.col -->
        </div>
        <!-- info row -->

        <div class="row invoice-info d-flex justify-content-between">
         
          
          <!-- /.col -->
          <div class="col-lg-6 invoice-col " style="padding-left: 40px;">
            <strong style="font-size: 28px;">Customer Details </strong>
            <address style="font-size: 16px !important;">
              <strong style="font-size: 16px !important;"></strong>
              {{ sale.shop.name }} ,{{ sale.shop.address }}
              ({{ sale.shop.owner_name }})<br>
              <b>Phone:</b> <span v-if="sale.shop.contact == '0'">N/A</span>
              <span v-else style="font-size: 16px !important;">{{ sale.shop.contact }}</span> <br>
              <b>Tell: </b><span v-if="sale.shop.telephone_number == '0'" style="font-size: 24px !important;">N/A</span>
              <span v-else style="font-size: 16px !important;"> {{ sale.shop.telephone_number }}</span>
            </address>
          </div>
          <div class="col-lg-4 invoice-col" style="font-size: 16px;">

            <b>Invoice :</b> MH{{ sale.sale_id }}
            <br>
            <b>Order ID: </b> MH{{ sale.sale_id }}<br>
            <b>Ref Name : </b> {{ sale.ref_name }}<br>
            <b>Ref Contact :</b> {{ sale.ref_phone_number }}
          </div>

        </div>

        <!-- /.row -->

        <!-- Table row -->

        <div class="row mt-2">
          <div class="col-12 table-responsive">
            <table class="table table-striped" style="font-size: 16px;">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Product Name</th>
                  <th>Qty</th>
                  <th>Unit Price (.Rs)</th>
                  <th>Subtotal (.Rs)</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in sale.items" :key="index">
                  <td>{{ index + 1 }}</td>
                  <td style="max-width: 100px;">{{ item.product_name }} </td>
                  <td>{{ item.quantity }}</td>
                  <td> {{ item.adjustPrise == 0 ? ((item.quantity * item.price).toFixed(2) / item.quantity) : ((item.quantity * item.adjustPrise).toFixed(2) / item.quantity) }}</td>
                  <td>{{  item.adjustPrise == 0 ? (item.quantity * item.price).toFixed(2) : (item.quantity * item.adjustPrise).toFixed(2) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
          <!-- accepted payments column -->
          <div class="col-6">

          </div>
          <!-- /.col -->
          <div class="col-6">
            <!-- <p class="lead">Amount Due ...................................................</p> -->

            <div class="table-responsive">
              <table class="table">
                <tbody>
                  <tr>
                    <th style="width:50%; font-size: 16px;">Subtotal:</th>
                    <td style="font-size: 16px;">{{ sale.total_price }}.Rs</td>
                  </tr>
                  <!-- <tr>
                    <th>Tax (9.3%)</th>
                    <td>N/A</td>
                  </tr>
                  <tr>
                    <th>Shipping:</th>
                    <td>N/A</td>
                  </tr> -->
                  <tr>
                    <th style="font-size: 16px;">Total:</th>
                    <td style="font-size: 16px;">{{ sale.total_price }}.RS</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <!-- /.col -->
        </div>
        <div class="row" style="margin-top: 350px; font-size: 16px;">
          <div class="col-6">
            <p>..................................</p>
            <p>Customer Signature</p>
          </div>
        </div>
        <!-- /.row -->
        <!-- this row will not appear when printing -->
        <div class="row no-print">
          <div class="col-12 d-flex justify-content-end">
            <a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-default" @click="printPage()"><i
                class="fas fa-print"></i> Print</a>
          </div>
        </div>
      </div>
    </div>

  </div>

</template>

<script>
import { types } from 'admin-lte/plugins/chart.js/Chart.min';

export default {
  props: {
    sale: {
      type: Object,
      required: true,
    },
  },

  methods: {
    printPage() {
      const originalTitle = document.title;
      document.title = "Sales Print";
      window.print();
      document.title = originalTitle;
    },
  },
};
</script>

<style scoped>
/* Default styles for the component */
#print-content {
  padding: 20px;
  background-color: #fff;
}

address {
  font-size: 20px !important;
}

.table td,
.table th {
  padding: 0.20rem !important;
}

/* Custom print styles */
@media print {

  /* Remove margins and padding applied by browsers during printing */
  @page {
    margin: 0;
    margin-bottom: 30px;
  }

  /* Ensure content spans the full width of the page */
  body {
    border: none;
  }

  /* Customize the printed content */
  #print-content {
    border: none;
    /* Remove any border */
  }

  /* Hide buttons or unnecessary elements */
  button {
    display: none;
  }

}
</style>