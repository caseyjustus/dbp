<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Language\AlphabetNumber
 *
 * @mixin \Eloquent
 *
 * @OAS\Schema (
 *     type="object",
 *     description="Alphabet Number",
 *     title="Alphabet Number",
 *     @OAS\Xml(name="AlphabetNumber")
 * )
 *
 */
class AlphabetNumber extends Model
{
    protected $table = "alphabet_numbers";
    protected $hidden = ["created_at","updated_at","id"];
    protected $fillable = [
    	"script_id",
	    "numeral",
	    "numeral_vernacular",
	    "numeral_written"
    ];

	/**
	 * @property string id
	 * @method static AlphabetNumber whereId($value)
	 *
	 * @OAS\Property(
	 *     title="Incrementing Alphabet Number Id",
	 *     description="The url to the font file",
	 *     format="string",
	 *     maxLength=191
	 * )
	 *
	 */
    protected $id;
	/**
	 * @property string script_id
	 * @method static AlphabetNumber whereScriptId($value)
	 *
	 * @OAS\Property(
	 *     title="Alphabet Script Id",
	 *     description="The url to the font file",
	 *     format="string",
	 *     maxLength=191
	 * )
	 *
	 */
    protected $script_id;
	/**
	 * @property string script_variant_iso
	 * @method static AlphabetNumber whereScriptVariantIso($value)
	 *
	 * @OAS\Property(
	 *     title="Alphabet Script Variant Iso",
	 *     description="The url to the font file",
	 *     format="string",
	 *     maxLength=3
	 * )
	 *
	 */
    protected $script_variant_iso;
	/**
	 * @property string numeral
	 * @method static AlphabetNumber whereNumeral($value)
	 *
	 * @OAS\Property(
	 *     title="Alphabet Numeral",
	 *     description="The url to the font file",
	 *     format="integer"
	 * )
	 *
	 */
    protected $numeral;
	/**
	 * @property string numeral_vernacular
	 * @method static AlphabetNumber whereNumeralVernacular($value)
	 *
	 * @OAS\Property(
	 *     title="Alphabet Numeral Vernacular",
	 *     description="The numeral written out the vernacular translations",
	 *     format="string",
	 *     maxLength=12
	 * )
	 *
	 */
    protected $numeral_vernacular;
	/**
	 * @property string numeral_written
	 * @method static AlphabetNumber whereNumeralWritten($value)
	 *
	 * @OAS\Property(
	 *     title="Alphabet Numeral Written",
	 *     description="The word for the numeral written out within the vernacular of the language",
	 *     format="string",
	 *     maxLength=24
	 * )
	 *
	 */
    protected $numeral_written;

	/*
	 * @property \Carbon\Carbon|null $created_at
	 * @property \Carbon\Carbon|null $updated_at
	*/
	protected $created_at;
	protected $updated_at;

}
