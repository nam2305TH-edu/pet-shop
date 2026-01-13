<?php
class ProductType extends Controller{
    private function containsHtml($value) {
        return $value !== strip_tags($value);
    }

    public function show(){
        $obj = $this->model("AdProductTypeModel");
        $searchTerm = isset($_GET['search_maLoai']) ? trim($_GET['search_maLoai']) : '';
        if ($searchTerm !== '' && $this->containsHtml($searchTerm)) {
            $_SESSION['pt_error'] = 'Không hợp lệ: không được nhập mã HTML trong thông tin tìm kiếm';
            header("Location:" . APP_URL . "/ProductType/");
            exit();
        }

        $data = $searchTerm === ''
            ? $obj->all("tblloaisp")
            : $obj->searchByCode($searchTerm);

        $productCounts = $obj->getProductCountsByType();
        
        $this->view("adminPage",["page"=>"ProductTypeView","productList"=>$data, 'productCounts' => $productCounts, 'searchTerm' => $searchTerm]);
    }
    
    public function delete($id){
        $obj = $this->model("AdProductTypeModel");
        $obj->query("DELETE FROM tblsanpham WHERE maLoaiSP = :maLoaiSP", [':maLoaiSP' => $id]);
        $obj->delete("tblloaisp",$id);
        header("Location:".APP_URL."/ProductType/");    
        exit();
    }
    
    public function deleteProductType($id){
        $obj = $this->model("AdProductTypeModel");
        $obj->query("DELETE FROM tblsanpham WHERE maLoaiSP = :maLoaiSP", [':maLoaiSP' => $id]);
        $obj->delete("tblloaisp",$id);
        header("Location:".APP_URL."/ProductType/");    
        exit();
    }
    
    public function create(){
        $txt_maloaisp = isset($_POST["txt_maloaisp"]) ? trim($_POST["txt_maloaisp"]) : "";
        $txt_tenloaisp = isset($_POST["txt_tenloaisp"]) ? trim($_POST["txt_tenloaisp"]) : "";
        $txt_motaloaisp = isset($_POST["txt_motaloaisp"]) ? trim($_POST["txt_motaloaisp"]) : "";

        $obj = $this->model("AdProductTypeModel");

        if ($txt_maloaisp === "" || $txt_tenloaisp === "") {
            $_SESSION['pt_error'] = 'Vui lòng nhập đầy đủ Mã loại SP và Tên loại SP';
            header("Location:".APP_URL."/ProductType/");
            exit();
        }

        if ($this->containsHtml($txt_maloaisp) || $this->containsHtml($txt_tenloaisp) || $this->containsHtml($txt_motaloaisp)) {
            $_SESSION['pt_error'] = 'Không hợp lệ: không được nhập mã HTML trong thông tin loại sản phẩm';
            header("Location:".APP_URL."/ProductType/");
            exit();
        }

        // Kiểm tra trùng mã
        $existing = $obj->find("tblloaisp", $txt_maloaisp);
        if ($existing) {
            $_SESSION['pt_error'] = 'Mã loại sản phẩm đã tồn tại. Vui lòng chọn mã khác';
            header("Location:".APP_URL."/ProductType/");
            exit();
        }

        // Thêm với email rỗng để tránh NOT NULL
        $obj->insert($txt_maloaisp, $txt_tenloaisp, $txt_motaloaisp, "");
        $saved = $obj->find("tblloaisp", $txt_maloaisp);
        if ($saved) {
            $_SESSION['pt_success'] = 'Thêm loại sản phẩm thành công';
        } else {
            $_SESSION['pt_error'] = 'Đã cố gắng thêm nhưng dữ liệu chưa lưu vào database. Vui lòng thử lại.';
        }
        header("Location:".APP_URL."/ProductType/");    
        exit();
    }
    
    public function edit($maLoaiSP)
    {
        $obj = $this->model("AdProductTypeModel");
        $product = $obj->find("tblloaisp", $maLoaiSP);
        $productList = $obj->all("tblloaisp");
        $productCounts = $obj->getProductCountsByType();
        
        $this->view("adminPage",["page"=>"ProductTypeView",
                            'productList' => $productList,
                            'editItem' => $product,
                            'productCounts' => $productCounts]);
    }
    
    public function update($maLoaiSP)
    {
        $newMaLoaiSP = trim($_POST['txt_maloaisp'] ?? '');
        $tenLoaiSP = trim($_POST['txt_tenloaisp'] ?? '');
        $moTaLoaiSP = trim($_POST['txt_motaloaisp'] ?? '');

        if ($newMaLoaiSP === '') {
            $_SESSION['pt_error'] = 'Vui lòng nhập Mã loại SP';
            header("Location:".APP_URL."/ProductType/");    
            exit();
        }

        if ($this->containsHtml($newMaLoaiSP) || $this->containsHtml($tenLoaiSP) || $this->containsHtml($moTaLoaiSP)) {
            $_SESSION['pt_error'] = 'Không hợp lệ: không được nhập mã HTML trong thông tin loại sản phẩm';
            header("Location:".APP_URL."/ProductType/");    
            exit();
        }
        
        $obj = $this->model("AdProductTypeModel");

        if ($newMaLoaiSP !== $maLoaiSP) {
            $existing = $obj->find("tblloaisp", $newMaLoaiSP);
            if ($existing) {
                $_SESSION['pt_error'] = 'Mã loại sản phẩm mới đã tồn tại. Vui lòng chọn mã khác';
                header("Location:".APP_URL."/ProductType/");
                exit();
            }
        }

        try {
            $obj->updateWithKeyChange($maLoaiSP, $newMaLoaiSP, $tenLoaiSP, $moTaLoaiSP);
            $_SESSION['pt_success'] = 'Cập nhật loại sản phẩm thành công';
        } catch (Exception $e) {
            $_SESSION['pt_error'] = 'Lỗi khi cập nhật loại sản phẩm: ' . $e->getMessage();
        }
        header("Location:".APP_URL."/ProductType/");    
        exit();
    }

}

