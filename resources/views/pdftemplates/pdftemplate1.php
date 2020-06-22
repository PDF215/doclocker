<div class="invoice" style="width: 700px;
  border: 1px solid #000000;
  margin: auto;
  padding: 30px;">
    <div class="invoice-logo" style="width: 100%">
        <img src="http://panda-doc.com.loc/<?php echo $data["invoice_image"]; ?>" alt="" style="width: 100px;">
    </div>
    <div class="invoice-sec-1" style="width: 100%;
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;">
        <div class="invoice-sec-1-ref" style="width: 50%;">
            <div class="ref-no">
                <p>Ref: Association Member - <span>A754</span></p>
                <p>Invoice No: <span><?php echo $data["invoice_number"]; ?></span></p>
            </div>
            <!--<div class="to-invoice" style="margin-top: 85px;padding-left: 42px;">
                <p>To</p>
                <p>SMART INNOVATION LIMITED</p>
                <p>Attn: <span>Mr. ABM Mohiuddin, Dicrector</span></p>
            </div>-->
        </div>
        <div class="invoice-sec-1-date" style="width: 50%;">
            <p style="position: relative;
  top: -107px;
  text-align: right;">Date: <span><?php echo $data["created_at"]; ?></span></p>
        </div>
    </div>
    <div class="invoice-banner" style=" margin: 5px;
  width: 100%;">
        <div class="banner-d" style="width: 200px;
  border: 2px solid #000000;
  border-radius: 5px;
  margin: auto;
  padding: 5px;
  display: flex;
  justify-content: center;
  align-items: center;">
            <p style="font-weight: bold;
  margin: 0px;">Invoice</p>
        </div>
    </div>
    <div class="invoice-table" style="display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  margin-top: 40px;">
        <div class="invoice-table-container" style=" width: 100%; margin: auto;">
            <div class="invoice-table-data" style="display: flex; flex-direction: row;">
                <div class="invoice-table-sl invoice-table-sl-h" style="text-align: center;
  width: 20%;
  border: .5px solid #000000;
  border-left: 1px solid #000000 !important;border-top: 1px solid #000000 !important;">
                    <strong><p style="padding: 0;">Sl. No</p></strong>
                </div>
                <div class="invoice-table-desc invoice-table-desc-h" style=" border-top: 1px solid #000000 !important;text-align: center;
  width: 60%;
  border: .5px solid #000000;">
                    <strong><p style="padding: 0;">Description</p></strong>
                </div>
                <div class="invoice-table-amount invoice-table-amount-h" style="  border-top: 1px solid #000000 !important;
  border-right: 1px solid #000000 !important;text-align: center;
  width: 20%;
  border: .5px solid #000000;
  border-right: 1px solid #000000 !important;">
                    <strong><p style="padding: 0;">Amount</p></strong>
                </div>
            </div>
            <div class="invoice-table-data" style="display: flex; flex-direction: row;">
                <div class="invoice-table-sl" style="text-align: center;
  width: 20%;
  border: .5px solid #000000;
  border-left: 1px solid #000000 !important;">
                    <p style="padding: 0;">01</p>
                </div>
                <div class="invoice-table-desc" style="text-align: center;
  width: 60%;
  border: .5px solid #000000;">
                    <p style="padding: 0;"><?php echo $data["table_description"]; ?></p>
                </div>
                <div class="invoice-table-amount" style="text-align: center;
  width: 20%;
  border: .5px solid #000000;
  border-right: 1px solid #000000 !important;">
                    <p style="padding: 0;">$<?php echo $data["table_content"]; ?></p>
                </div>
            </div>
            <div class="invoice-table-footer" style="border: 1px solid #000000;
  display: flex;
  flex-direction: row;
  border-top: .5px solid #000000 !important;">
                <div class="invoice-total" style="text-align: center;
  width: 80%;">
                    <p style="padding: 0;">Total</p>
                </div>
                <div class="invoice-total-amount" style="text-align: center;
  width: 20%;border: 1px solid #000000;
  border-right: 0px solid #000000 !important;">
                    <p style="padding: 0;">$<?php echo $data["table_content"]; ?></p>
                </div>
            </div>
            <p style="padding: 0;">Title :<span><?php echo $data["invoice_title"]; ?></span></p>
        </div>
    </div>
    <div class="invoice-declaration" style="text-align: center;">
        <p><?php echo $data["invoice_description"]; ?></p>
    </div>
    <div class="invoice-greeting" style="  margin-top: 70px;">
        <p>Contact Name :<?php echo $username ?></p>
        <p>Sign : <?php echo $data["sign"]; ?></p>
    </div>
</div>
