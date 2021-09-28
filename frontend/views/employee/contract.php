<?php

use common\models\entity\Employee;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\entity\Contract */

$this->title = $title;
?>

<div class="text-center">
	<h4>
		<b>
			<u>PERJANJIAN KERJA WAKTU TERTENTU</u>
			<br>No.<?= $model->contract_number ?>
		</b>
	</h4>
</div>

<br>
<p>Yang bertanda tangan dibawah ini:</p>

<div style="margin-left:20px">
	<table class="table-report-noborder">
		<tr><td align="right" width="20px">I.&nbsp;&nbsp;</td><td width="150px">Nama  </td> <td>:</td><td> <?= $model->signer_name ?> </td></tr>
		<tr><td></td><td>Jabatan             </td> <td>:</td><td> <?= $model->signer_position ?> </td></tr>
		<tr><td></td><td>Alamat              </td> <td>:</td><td> <?= $model->signer_address ?> </td></tr>
	</table>
	<p>Bertindak untuk dan atas nama PT. Salam Wadah Karya, selanjutnya disebut <b>Pihak Pertama.</b></p>

	<table class="table-report-noborder">
		<tr><td align="right" width="20px">II.&nbsp;&nbsp;</td><td width="150px">Nama  </td><td>:</td><td> <?= $model->employee->name ?> </td></tr>
		<tr><td></td><td>Tempat / Tgl Lahir  </td><td>:</td><td> <?= $model->employee->place_of_birth ?> / <?= Yii::$app->formatter->asDate($model->employee->date_of_birth) ?> </td></tr>
		<tr><td></td><td>Jenis kelamin       </td><td>:</td><td> <?= Employee::sexes($model->employee->sex) ?> </td></tr>
		<tr><td></td><td>Jabatan             </td><td>:</td><td> <?= $model->contractPlacements[0]->position ?> </td></tr>
		<tr><td></td><td>NRK                 </td><td>:</td><td> <?= $model->employee->registration_number ?> </td></tr>
		<tr><td></td><td>Alamat              </td><td>:</td><td> 
															<?= $model->employee->address_street ?>, 
															<?= $model->employee->address_neighborhood ?>, 
															<?= $model->employee->addressVillage->name ?>, 
															<?= $model->employee->addressSubdistrict->name ?>, 
															<?= $model->employee->addressDistrict->name ?>, 
															<?= $model->employee->addressProvince->name ?> 
														</td></tr>
	</table>
	<p>Bertindak untuk dan atas namanya sendiri, selanjutnya disebut <b>Pihak Kedua.</b></p>
</div>

<br>
<p>Para Pihak dengan ini mengadakan persetujuan bersama membuat Perjanjian Kerja, dan menghasilkan sebagai berikut: </p>

<br>
<div class="text-center">
    <b><br>Pasal 1<br>Jabatan / Jenis Pekerjaan dan Penempatan</b>
</div>
<br>

<ol type="1">
    <li>
        Pihak Pertama memberikan pekerjaan kepada Pihak Kedua, dan Pihak Kedua menerima pekerjaan dari Pihak Pertama
        <table class="table-report-noborder" style="margin-left:20px">
        <tr><td>sebagai			    </td><td>:</td><td> <?= $model->contractPlacements[0]->position ?> </td></tr>			
        <tr><td>yang ditugaskan di	</td><td>:</td><td> <?= $model->contractPlacements[0]->client->name ?> </td></!																 								 -->
        <tr><td>lokasi tugas		</td><td>:</td><td> <?= $model->contractPlacements[0]->location ?> </td></tr>				
        </table>																				
        serta pihak Kedua sanggup melaksanakan pekerjaan dengan sebaik-baiknya serta bersedia mentaati segala ketentuan dan tata tertib yang berlaku seperti yang tercantum dalam pasal-pasal perjanjian ini.	
    </li>
    <li>
        Pihak Pertama berhak menempatkan Pihak  Kedua   pada   tugas - tugas   pekerjaan  yang  lain  sesuai  dengan kemampuannya,  dan Pihak Kedua bersedia ditempatkan dan atau dimutasikan kebagian lain yang dipandang sesuai dengan kebutuhan Perusahaan.	
    </li>
</ol>

<br>
<div class="text-center">
    <b><br>Pasal 2<br>Jangka Waktu dan Berakhirnya Perjanjian Kerja</b>
</div>
<br>

<ol type="1">
    <li>
		Jangka waktu Perjanjian adalah: 
        <table class="table-report-noborder" style="margin-left:20px">
        <tr><td>a. Durasi Perjanjian 	</td><td>:</td><td> <?= $model->duration ?> </td></tr>			
        <tr><td>b. Terhitung mulai		</td><td>:</td><td> <?= Yii::$app->formatter->asDate($model->started_at) ?> SD <?= Yii::$app->formatter->asDate($model->ended_at) ?> </td></tr>	
        </table>									
		di mana Pihak Pertama akan mengevaluasi kinerja Pihak Kedua setiap akhir bulan.
    </li>
    <li>Perjanjian kerja ini berakhir sesuai dengan ketentuan pasal 2 ayat 1 tersebut diatas.</li>
    <li>
		Pihak Pertama berhak memutuskan hubungan kerja ini sebelum  berakhirnya jangka  waktu  Perjanjian,  tanpa  kewajiban membayar kompensasi apapun kepada Pihak Kedua, dalam hal: 	
		<ol type="a">
			<li>Pihak Kedua melanggar aturan disiplin, sesuai tercantum dalam Peraturan Perusahaan dengan mengindahkan Undang-Undang atau Peraturan Pemerintah</li>
			<li>Pihak Kedua tidak mampu melaksanakan tugas dan tanggung jawabnya sesuai dengan kualifikasi yang ditetapkan oleh Pihak Pertama, atau karena</li>
			<li>Dari hasil evaluasi ternyata bahwa kinerja Pihak Kedua kurang atau buruk</li>
			<li>Pihak kedua melakukan kesalahan berat (sesuai Permenaker RI No.  Per - 02 / Men / 1993  pasal 17, 18 dan 19).</li>
			<li>Pihak Kedua diangkat menjadi karyawan mitra Perusahaan dimana Pihak Kedua ditempatkan</li>
			<li>Atas permintaan mitra Perusahaan Pihak Kedua dialihkan ke pihak lain.</li>
			<li>Pihak kedua keluar dari pekerjaan atau mengundurkan diri secara tiba-tiba tanpa pemberitahuan dan tanpa mengajukan pengunduran diri satu bulan sebelumnya.</li>
    </li>
    <li>Kesepakatan kerja ini juga dapat berakhir bersamaan apabila Perjanjian Kerja antar Pihak Pertama dengan Mitra Pihak Pertama dimana Pihak Kedua ditempatkan berakhir tanpa kompensasi apapun.</li>
    <li>Dalam  hal  terjadinya Efesiensi atau habisnya  masa  Perjanjian Kerja  baik di Mitra  Perusahaan  maupun  di lingkungan  Perusahaan Pihak   Pertama  maka  dengan  sendirinya  Pihak  Pertama  dapat   mengakhiri  Kesepakatan Kerja  antara  Pihak Pertama dengan Pihak Kedua tanpa kompensasi apapun.</li>
	<li>Pihak Kedua wajib menginformasikan masa berakhirnya Perjanjian kerja ini, 1 (satu) bulan sebelumnya atau selambat-lambatnya 2 (dua) minggu sebelum masa berakhirnya Perjanjian kerja ini.</li>
</ol>
	 

<br>
<div class="text-center">
    <b><br>Pasal 3<br>Upah Pekerjaan, Lembur dan Pajak</b>
</div>
<br>

<ol type="1">
    <li>
		Pihak Pertama akan membayar upah secara bulanan  kepada Pihak Kedua dengan perincian sebagai berikut: 
        <table class="table-report-noborder" style="margin-left:20px">
		<?php foreach ($model->contractSalaries as $salary): ?>
		<?php if ($salary->type <= 2): ?>
			<tr><td><?= $salary->name ?></td> <td>:</td> <td align="right">Rp <?= Yii::$app->formatter->asDecimal($salary->amount, 0) ?></td></tr>
		<?php endif; ?>
		<?php endforeach; ?>
        </table>									
		<i>Catatan: Gaji Bersifat Pribadi dan  Rahasia</i>
    </li>
    <?php if ($model->pasal_3_2) { ?> <li>Pihak Pertama akan membayar lembur kepada Pihak Kedua sesuai dengan Peraturan Pemerintah yang berlaku yaitu Kepmen No.102/Men/VI/2004.(Cut off absensi dari tgl 1-30 bulan sebelumnya).</li> <?php } ?>
	<li>Apabila masuk kerja tidak dari awal bulan dan berhenti tidak sampai akhir bulan maka dihitung secara proporsional, contoh joint tgl 15, GP yang diterima <?= $model->pasal_3_3-15 ?>/<?= $model->pasal_3_3 ?> X GP, keluar tgl 15, GP yang diterima 15/<?= $model->pasal_3_3 ?> X GP.</li>
	<li>Gaji Pokok  dihitung satu bulan berjalan (1-30) , apabila masuk dari tanggal 1 - 15 maka pembayaran gaji akan dilakukan dibulan tersebut dan jika masuk dari tanggal 16 - 30 maka pembayaran gaji dilakukan atau dirapel di bulan berikutnya.</li>
	<li>Bila Hari Raya Keagamaan jatuh pada masa berlakunya Perjanjian ini, Pihak Kedua akan diberikan Tunjangan Hari Raya (THR) yang masa kerja minimal 1 Bulan atau (30 hari) terhitung sejak tanggal mulai bekerja secara terus-menerus atau lebih dan bila masa kerja kurang dari 1 tahun maka pemberian THR dihitung secara prorata, dan bila masa kerja lebih dari 1 tahun THR akan dibayarkan 1 bulan gaji. Untuk pembayarannya selambat-lambatnya 7 (tujuh) hari sebelum Hari Raya  Idul Fitri.</li>
	<li>Seluruh penghasilan atau Upah Pihak Kedua setiap bulan dikenakan pajak  penghasilan yang akan dipotong dan disetorkan oleh Pihak Pertama yang nilainya sesuai  UU perpajakan yang berlaku.</li>
	<li>Seluruh penghasilan atau Upah Pihak Kedua akan dibayar tanggal <?= $model->payment_date ?> setiap bulannya.</li>
	<li>Pihak KEDUA yang tidak bekerja atau tidak masuk kerja tanpa didasari bukti dan ketentuan yang kuat termasuk tidak mendapatkan ijin dari atasannya atau dinyatakan Alpha/Mangkir akan dipotong Upahnya sesuai dengan jumlah hari karyawan dinyatakan Alpha/Mangkir.</li>
</ol>


<br>
<div class="text-center">
    <b><br>Pasal 4<br>Waktu Kerja</b>
</div>
<br>

<ol type="1">
    <li>
		Waktu  kerja  Pihak Kedua  ditetapkan  sesuai  dengan ketentuan di perusahaan, dan  atau   mengikuti ketentuan dimana Pihak Kedua ditempatkan, termasuk ketentuan tentang kerja shift, bila diperlukan.
		<br>**(Untuk kelancaran aktivitas pekerjaan karyawan diharapkan hadir 15 menit lebih awal).
    </li>
	<li>Pihak kedua berhak menggunakan atau mengajukan hak cutinya setelah minimal masa kerja 4 (empat) bulan kepada Pihak Pertama dengan disertai persetujuan Pimpinan atau atasan dimana Pihak Pertama bekerja.</li>
</ol>
			

<br>
<div class="text-center">
    <b><br>Pasal 5<br>Asuransi Tenaga Kerja</b>
</div>
<br>
<ol type="1">
    <li>
		Pihak Pertama mendaftarkan Pihak Kedua menjadi peserta Badan Penyelenggara Jaminan Sosial (BPJS), yang meliputi:
		<ol type="I">
			<li>BPJS Ketenagakerjaan</li>
				<ol type="a">
					<li>Jaminan Kecelakaan Kerja, (JKK=0,24% ditanggung oleh perusahaan)</li>
					<li>Jaminan Kematian, (JKM=0,30% ditanggung oleh perusahaan)</li>
					<li>Jaminan Hari Tua, (JHT=5,7%): 2 %  ditanggung oleh Tenaga kerja, 3,7 % ditanggung oleh Perusahaan.</li>
					<li>Jaminan Pensiun, (JP = 3 % ): 1 %  ditanggung oleh Tenaga Kerja, 2 % ditanggung oleh Perusahaan.</li>
				</ol>
			<li>BPJS Kesehatan 
				<br>( 5 % ): 1 % ditanggung oleh Tenaga Kerja, 4 % ditanggung oleh Perusahaan.
			</li>
		</ol>
	</li>
	<li>Ketentuan mengenai iuran dan hal - hal yang mengenai pelaksanaannya, diatur sesuai Undang - Undang No. 24 Tahun 2011.</li>
	<li>Pihak Pertama mendaftarkan Pihak Kedua menjadi peserta Asuransi Kecelakaan di luar Hari Kerja (AKDHK).</li>
	<li>Untuk fasilitas BPJS kesehatan, dapat dipergunakan setelah kartu peserta selesai dibuat.</li>
</ol>


<br>
<div class="text-center">
    <b><br>Pasal 6<br>Peraturan Tata Tertib</b>
</div>
<br>
<ol type="1">
    <li>
		Pihak Kedua wajib memberitahukan kepada Pihak Pertama data pribadi bila ada perubahan data pribadi yang meliputi:	
		<ol type="a">
			<li>Alamat tempat tinggal</li>
			<li>Keadaan keluarga seperti perkawinan, kelahiran, kematian, dan lain-lain</li>
			<li>Anggota keluarga terdekat</li>
			<li>Salinan Kartu Tanda Penduduk (KTP)</li>
		</ol>
    </li>
	<li>Perlengkapan safety dan seragam yang diberikan, pada saat Pihak Kedua keluar dari pekerjaannya diwajibkan menyerahkan perlengkapan tersebut kepada Pihak Pertama dalam kondisi baik.</li>
	<li>Pihak Kedua wajib mentaati Peraturan Kerja di dalam perusahaan.</li>
	<li>Pihak Kedua wajib mentaati Kesehatan, kebersihan dan Keselamatan Kerja yang ditentukan didalam lingkungan perusahaan, serta mencegah dan tidak melakukan tindakan-tindakan yang membahayakan diri sendiri maupun keselamatan pekerja lainnya.</li>
	<li>Pihak Kedua wajib mentaati peraturan keamanan dalam perusahaan, serta mencegah dan tidak melakukan tindakan-tindakan yang dapat menimbulkan terjadinya kebakaran, pencurian, kehilangan atau perusakan, provokasi dan perkelahian.</li>
</ol>	



<br>
<div class="text-center">
    <b><br>Pasal 7<br>Tindakan Disiplin</b>
</div>
<br>
<ol type="1">
    <li>Dalam rangka menegakan aturan kerja dan menjaga hubungan kerja yang sehat, Pihak Pertama, memberikan sanksi terhadap pelanggaran yang dilakukan oleh Pihak Kedua atas peraturan atau tata tertib yang berlaku didalam perusahaan.</li>
	<li>
		Sanksi yang akan diberikan adalah dalam bentuk:
		<ol type="a">	
			<li>Peringatan lisan</li>
			<li>Peringatan tertulis</li>
			<li>Pemutusan Hubungan Kerja (PHK)</li>
		</ol>
	</li>
	<li>Sanksi peringatan lisan dilakukan oleh atasan Pihak Kedua untuk pelanggaran yang bersifat umum</li>
	<li>
		Sanksi peringatan tertulis dilakukan oleh atasannya bila Pihak Kedua melakukan tindakan-tindakan yang tersebut dibawah ini:	
		<ol type="a">
			<li>Terlambat masuk kerja tanpa alasan yang dapat diterima, serta terlambat masuk kerja selama 4 kali tidak berturut-turut dalam satu bulan</li>
			<li>Mencetak kartu hadir orang lain atau kartu hadirnya diisikan orang lain dengan sepengetahuannya.</li>
			<li>Selama jam kerja sering meninggalkan tempat kerja tanpa ijin atau alasan yang sah meskipun telah diberikan peringatan lisan oleh atasannya.</li>
			<li>Pulang lebih awal dari waktu yang sudah ditentukan tanpa ijin atau alasan yang sah atau dengan alasan yang dibuat-buat sebanyak 3 (tiga) kali dalam sebulan meskipun telah diberikan peringatan lisan oleh atasannya.</li>
			<li>Tetap tidak menunjukkan kesungguhan bekerja walaupun sudah diberikan peringatan lisan oleh atasannya.</li>
			<li>Tidur di dalam atau diluar lingkungan pekerjaan pada waktu jam kerja.</li>
			<li>Tidak mengenakan tanda pengenal, pakaian kerja, sepatu safety dan perlengkapan keselamatan kerja yang sudah diberikan pada waktu melakukan pekerjaan.</li>
			<li>Tidak memakai perlengkapan keselamatan kerja dan kesehatan pada waktu melakukan pekerjaan meskipun telah diberikan peringatan lisan oleh atasannya.</li>
			<li>Menjual kupon berhadiah  atau sejenisnya   didalam    lingkungan Perusahaan meskipun telah diberikan peringatan lisan oleh atasannya.</li>
			<li>Memaksa orang lain untuk melakukan pekerjaannya dan atau melakukan pekerjaan yang  bukan tugasnya tanpa ijin atau perintah atasannya, seperti mengendarai kendaraan truck, forklift, serta kendaraan angkutan lainnya yang bukan menjadi tugasnya.</li>
			<li>Tidak melaporkan kepada atasannya atau tidak mengambil tindakan pencegahan atas perbuatan atau tindakan  sesama  pekerja  atau  orang lain yang  diketahuinya   didalam   Perusahaan,  yang   dapat menimbulkan bahaya bagi sesama pekerja atau merugikan Perusahaan.</li>
		</ol>
	</li>	
	<li>
		Pelaksanaan sanksi pemberhentian kerja dikenakan terhadap Pihak Kedua yang melakukan kesalahan atau pelanggaran berat yang pelaksanaannya diatur sebagai berikut:	
		<ol type="a">
			<li>Pelaksanaan sanksi pemberhentian perjanjian kerja disampaikan secara tertulis oleh Pihak Pertama.</li>
			<li>Pihak Pertama akan mengakhiri perjanjian kerja Pihak Kedua yang telah melakukan pelanggaran-pelanggaran berat seperti:
				<ol type="1">
					<li>Tidak bersedia dan atau mengindahkan pasal 1 ayat 2 ketentuan tersebut diatas.</li>
					<li>Tetap melakukan kesalahan atau pelanggaran yang sama atau yang lebih tinggi walaupun sudah diberikan sanksi peringatan tertulis.</li>
					<li>Meninggalkan pekerjaan tanpa ada informasi yang jelas dan tidak dapat dipertanggung jawabkan selama 5 (lima) hari berturut-turut sehingga pihak perusahaan mengalami kerugian baik di sisi immateri maupun materi.</li>
					<li>Tidak masuk kerja selama 5 (Lima) hari kerja berturut-turut tanpa keterangan yang dapat diterima maka Pihak Pertama berhak memberhentikan Pihak Kedua atau dikualifikasikan mengundurkan diri secara sepihak.</li>
					<li>Menganiaya pengusaha, keluarga pengusaha, atasan, dan teman kerja.</li>
					<li>Memaksa atau mengeluarkan ancaman kepada pengusaha, keluarga pengusaha, atasan, dan teman sekerja untuk melakukan tindakan yang bertentangan dengan Undang-Undang yang berlaku dilingkungan Perusahaan yang menimbulkan ketidaktenangan, ketidaktentraman jiwa.</li>
					<li>Dengan sengaja merusak barang milik Perusahaan  walaupun telah diberikan peringatan.</li>
					<li>Membongkar atau membocorkan rahasia perusahaan yang dipercayakan kepadanya atau yang diketahui dari pihak lain sehingga mengakibatkan kerugian bagi Perusahaan.</li>
					<li>Mabuk atau membawa obat-obatan terlarang kelingkungan perusahaan.</li>
					<li>Menerima suap atau pemberian apapun dari siapapun atau mencari keuntungan untuk diri sendiri dengan menggunakan jabatan melakukan hal-hal yang merugikan atau mengurangi keuntungan perusahaan.</li>
					<li>Melakukan tindakan Asusila di dalam lingkungan perusahaan.</li>
					<li>Memberikan keterangan palsu sehingga merugikan perusahaan.</li>
					<li>Merokok pada tempat-tempat yang diberi tanda "Dilarang Merokok".</li>
					<li>Dengan sengaja membiarkan orang lain terkena kecelakaan dari pekerjaannya.</li>
					<li>Berkelahi dengan sesama pekerja di lingkungan Perusahaan.</li>
					<li>Membawa senjata api atau senjata tajam ke dalam lingkungan Perusahaan.</li>
					<li>Tidak menunjukkan perbaikan atau tetap melakukan kesalahan atau pelanggaran kembali meskipun telah diberi peringatan terakhir.</li>
					<li>Melakukan pencurian dan pengelapan didalam lingkungan perusahaan serta setelah dilakukan investigasi secara hukum, maka pihak kedua berkewajiban untuk menggantinya dan diproses sesuai hukum yang berlaku.</li>
				</ol>
			</li>
		</ol>
	</li>
</ol>
	

<br>
<div class="text-center">
    <b><br>Pasal 8<br>Ketentuan Tambahan</b>
</div>
<br>
<p>
	Bila di kemudian hari timbul permasalahan dalam kaitan dengan hubungan kerja ini dan belum diatur dalam kesepakatan kerja atau peraturan Perusahaan, maka akan diselesaikan dengan cara musyawarah antara kedua belah pihak.		
	Kesepakatan kerja ini dibuat dan ditanda tangani di Jakarta oleh kedua belah pihak dalam keadaan sadar serta tanpa paksaan dari pihak manapun.
</p>
			
<br>
<br>
<table width="100%">
	<tr>
		<td width="50%" align="center">Pihak Pertama	<br><br><br><br><br><br></td>
		<td width="50%" align="center">Pihak Kedua		<br><br><br><br><br><br></td>
	</tr>
	<tr>
		<td width="50%" align="center"><u><b><?= $model->signer_name ?><b></u> <br><?= $model->signer_position ?></td>
		<td width="50%" align="center"><u><b><?= $model->employee->name ?><b></u> <br>Pekerja</td>
	</tr>
</table>