<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Santri
 * 
 * @property int $id
 * @property string|null $nis
 * @property string|null $nik
 * @property string|null $nama_lengkap
 * @property string|null $tempat_lahir
 * @property Carbon|null $tgl_lahir
 * @property string|null $gender
 * @property string|null $alamat
 * @property string|null $email
 * @property string|null $no_hp
 * @property Carbon|null $tgl_masuk
 * @property Carbon|null $tgl_keluar
 * @property Carbon|null $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 *
 * @package App\Models
 */
class Santri extends Model
{
	use SoftDeletes;

	protected $table = 'santri';
	protected $dates = ['deleted_at'];
	protected $casts = [
		'tgl_lahir' => 'datetime',
		'tgl_masuk' => 'datetime',
		'tgl_keluar' => 'datetime',
		'deleted_at' => 'datetime',
	];

	protected $fillable = [
		'nis',
		'nik',
		'nama_lengkap',
		'tempat_lahir',
		'tgl_lahir',
		'gender',
		'alamat',
		'email',
		'no_hp',
		'foto',
		'tgl_masuk',
		'tgl_keluar',
	];
}
