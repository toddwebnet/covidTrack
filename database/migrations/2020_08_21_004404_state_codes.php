<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StateCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('state_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('state_code');
            $table->string('state');
            $table->timestamps();
        });

        $sqls = [
            "insert into state_codes(state_code, state) values ('AL','Alabama')",
            "insert into state_codes(state_code, state) values ('AK','Alaska')",
            "insert into state_codes(state_code, state) values ('AZ','Arizona')",
            "insert into state_codes(state_code, state) values ('AR','Arkansas')",
            "insert into state_codes(state_code, state) values ('CA','California')",
            "insert into state_codes(state_code, state) values ('CO','Colorado')",
            "insert into state_codes(state_code, state) values ('CT','Connecticut')",
            "insert into state_codes(state_code, state) values ('DE','Delaware')",
            "insert into state_codes(state_code, state) values ('DC','District of Columbia')",
            "insert into state_codes(state_code, state) values ('FL','Florida')",
            "insert into state_codes(state_code, state) values ('GA','Georgia')",
            "insert into state_codes(state_code, state) values ('HI','Hawaii')",
            "insert into state_codes(state_code, state) values ('ID','Idaho')",
            "insert into state_codes(state_code, state) values ('IL','Illinois')",
            "insert into state_codes(state_code, state) values ('IN','Indiana')",
            "insert into state_codes(state_code, state) values ('IA','Iowa')",
            "insert into state_codes(state_code, state) values ('KS','Kansas')",
            "insert into state_codes(state_code, state) values ('KY','Kentucky')",
            "insert into state_codes(state_code, state) values ('LA','Louisiana')",
            "insert into state_codes(state_code, state) values ('ME','Maine')",
            "insert into state_codes(state_code, state) values ('MD','Maryland')",
            "insert into state_codes(state_code, state) values ('MA','Massachusetts')",
            "insert into state_codes(state_code, state) values ('MI','Michigan')",
            "insert into state_codes(state_code, state) values ('MN','Minnesota')",
            "insert into state_codes(state_code, state) values ('MS','Mississippi')",
            "insert into state_codes(state_code, state) values ('MO','Missouri')",
            "insert into state_codes(state_code, state) values ('MT','Montana')",
            "insert into state_codes(state_code, state) values ('NE','Nebraska')",
            "insert into state_codes(state_code, state) values ('NV','Nevada')",
            "insert into state_codes(state_code, state) values ('NH','New Hampshire')",
            "insert into state_codes(state_code, state) values ('NJ','New Jersey')",
            "insert into state_codes(state_code, state) values ('NM','New Mexico')",
            "insert into state_codes(state_code, state) values ('NY','New York')",
            "insert into state_codes(state_code, state) values ('NC','North Carolina')",
            "insert into state_codes(state_code, state) values ('ND','North Dakota')",
            "insert into state_codes(state_code, state) values ('OH','Ohio')",
            "insert into state_codes(state_code, state) values ('OK','Oklahoma')",
            "insert into state_codes(state_code, state) values ('OR','Oregon')",
            "insert into state_codes(state_code, state) values ('PA','Pennsylvania')",
            "insert into state_codes(state_code, state) values ('PR','Puerto Rico')",
            "insert into state_codes(state_code, state) values ('RI','Rhode Island')",
            "insert into state_codes(state_code, state) values ('SC','South Carolina')",
            "insert into state_codes(state_code, state) values ('SD','South Dakota')",
            "insert into state_codes(state_code, state) values ('TN','Tennessee')",
            "insert into state_codes(state_code, state) values ('TX','Texas')",
            "insert into state_codes(state_code, state) values ('UT','Utah')",
            "insert into state_codes(state_code, state) values ('VT','Vermont')",
            "insert into state_codes(state_code, state) values ('VA','Virginia')",
            "insert into state_codes(state_code, state) values ('WA','Washington')",
            "insert into state_codes(state_code, state) values ('WV','West Virginia')",
            "insert into state_codes(state_code, state) values ('WI','Wisconsin')",
            "insert into state_codes(state_code, state) values ('WY','Wyoming')"
        ];
        foreach ($sqls as $sql) {
            \Illuminate\Support\Facades\DB::insert($sql);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('state_codes');
    }
}
