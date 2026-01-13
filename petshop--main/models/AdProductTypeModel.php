<?php
require_once "BaseModel.php";
class AdProductTypeModel extends BaseModel{
    private $table="tblloaisp";
    public function insert($maLoaiSP, $tenLoaiSP, $moTaLoaiSP, $email = null) {
        // Kiểm tra bảng có trong danh sách không
        if (!array_key_exists($this->table, $this->primaryKeys)) {
            throw new Exception("Bảng không hợp lệ hoặc chưa được định nghĩa.");
        }
        // Kiểm tra xem mã loại sản phẩm đã tồn tại chưa
        $column = $this->primaryKeys[$this->table];
        if($this->check($this->table, $column, $maLoaiSP)){
            echo "Mã loại sản phẩm đã tồn tại. Vui lòng chọn mã khác.";
            return;
        }
        else{
            // Chuẩn bị câu lệnh INSERT
            $sql = "INSERT INTO tblloaisp (maLoaiSP, tenLoaiSP, moTaLoaiSP, email) 
                    VALUES (:maLoaiSP, :tenLoaiSP, :moTaLoaiSP, :email)";
            try {
                $stmt = $this->db->prepare($sql);
                // Gán giá trị cho các tham số
                $stmt->bindParam(':maLoaiSP', $maLoaiSP);
                $stmt->bindParam(':tenLoaiSP', $tenLoaiSP);
                $stmt->bindParam(':moTaLoaiSP', $moTaLoaiSP);
                $stmt->bindParam(':email', $email);
                // Thực thi câu lệnh
                $stmt->execute();
                echo "Thêm loại sản phẩm thành công.";
            } catch (PDOException $e) {
                echo "Thất bại" . $e->getMessage();
            } 
        }    
    }
    
    public function update($maLoaiSP, $tenLoaiSP, $moTaLoaiSP) {
        // Chuẩn bị câu lệnh UPDATE
        $sql = "UPDATE tblloaisp SET 
                tenLoaiSP = :tenLoaiSP, 
                moTaLoaiSP = :moTaLoaiSP
                WHERE maLoaiSP = :maLoaiSP";
        try {
            $stmt = $this->db->prepare($sql); 
            // Gán giá trị cho các tham số
            $stmt->bindParam(':maLoaiSP', $maLoaiSP);
            $stmt->bindParam(':tenLoaiSP', $tenLoaiSP);
            $stmt->bindParam(':moTaLoaiSP', $moTaLoaiSP);

            // Thực thi câu lệnh
            $stmt->execute();
            echo "Cập nhật loại sản phẩm thành công.";
        } catch (PDOException $e) {
            echo "Cập nhật không thành công: " . $e->getMessage();
        }
    }

    public function updateWithKeyChange($oldMaLoaiSP, $newMaLoaiSP, $tenLoaiSP, $moTaLoaiSP) {
        try {
            $this->db->beginTransaction();

            // Cập nhật bảng loại sản phẩm (khóa chính thay đổi)
            $sqlUpdateType = "UPDATE tblloaisp SET maLoaiSP = :newMaLoaiSP, tenLoaiSP = :tenLoaiSP, moTaLoaiSP = :moTaLoaiSP WHERE maLoaiSP = :oldMaLoaiSP";
            $stmt = $this->db->prepare($sqlUpdateType);
            $stmt->execute([
                ':newMaLoaiSP' => $newMaLoaiSP,
                ':tenLoaiSP' => $tenLoaiSP,
                ':moTaLoaiSP' => $moTaLoaiSP,
                ':oldMaLoaiSP' => $oldMaLoaiSP
            ]);

            // Cập nhật toàn bộ sản phẩm thuộc loại này
            $sqlUpdateProducts = "UPDATE tblsanpham SET maLoaiSP = :newMaLoaiSP WHERE maLoaiSP = :oldMaLoaiSP";
            $stmtProducts = $this->db->prepare($sqlUpdateProducts);
            $stmtProducts->execute([
                ':newMaLoaiSP' => $newMaLoaiSP,
                ':oldMaLoaiSP' => $oldMaLoaiSP
            ]);

            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getProductCountsByType(): array {
        $sql = "SELECT maLoaiSP, COUNT(*) AS total FROM tblsanpham GROUP BY maLoaiSP";
        $rows = $this->select($sql);
        $result = [];
        foreach ($rows as $row) {
            $result[$row['maLoaiSP']] = (int)$row['total'];
        }
        return $result;
    }

    public function searchByCode(string $keyword): array {
        $sql = "SELECT * FROM tblloaisp WHERE maLoaiSP LIKE :keyword";
        return $this->select($sql, [':keyword' => "%{$keyword}%"]);
    }
    
}
