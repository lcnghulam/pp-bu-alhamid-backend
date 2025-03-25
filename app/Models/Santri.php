<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Santri
 * 
 * @property int $id
 * @property string|null $nis
 * @property string|null $nama_lengkap
 * @property string|null $tempat_lahir
 * @property Carbon|null $tgl_lahir
 * @property string|null $gender
 * @property string|null $alamat
 * @property string|null $email
 * @property string|null $no_hp
 * @property Carbon|null $tgl_masuk
 * @property Carbon|null $tgl_keluar
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class Santri extends Model
{
	protected $table = 'santri';

	protected $casts = [
		'tgl_lahir' => 'datetime',
		'tgl_masuk' => 'datetime',
		'tgl_keluar' => 'datetime'
	];

	protected $fillable = [
		'nis',
		'nama_lengkap',
		'tempat_lahir',
		'tgl_lahir',
		'gender',
		'alamat',
		'email',
		'no_hp',
		'tgl_masuk',
		'tgl_keluar'
	];
}
