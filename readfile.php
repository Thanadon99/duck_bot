<?php
// การอ่านข้อมูลจาก Text File อีกรูปแบบนึง
$data=file("abc.txt");  // ข้อมูลที่ได้จากการใช้ Function file() จะได้ออกมาเป็น Array แต่ละบัีนทัดข้อมูลที่เก็บใน File คือ 1 ค่า index ของ Array
for($i=0;$i<count($data);$i++){  // วนรอบเพื่อแสดงผลขอ้มูล

echo $data[$i]."<br>";

} 
?> 