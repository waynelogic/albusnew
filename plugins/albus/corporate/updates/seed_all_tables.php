<?php namespace Albus\Corporate\Updates;

use Carbon\Carbon;

use October\Rain\Database\Updates\Seeder;

use Albus\Corporate\Models\Job;
use Albus\Corporate\Models\Department;
use Albus\Corporate\Models\Employee;

class SeedAllTables extends Seeder
{

    public function run()
    {
        Department::create([
            'name' => 'Дирекция'
        ]);
        Department::create([
            'name' => 'IT-отдел',
            'preview_text' => 'Наш IT отдел работает с крупными известными партнерами. Мы набираем в команду как опытных сотрудников, так и молодых специалистов. Вместе с нами вы будете вовлечены в интересные серьезные и, конечно же, прибыльные проекты.'
        ]);

        Job::create([
            'name' => 'Стажер PHP-программист',
            'preview_text' => 'Ищем программиста для внутренних проектов компании. В большинстве случаев ваши задачи будут касаться бэкэнда, но бояться фротэнда вы тоже не должны.',
            'salary' => '25 000 р.',
            'experience' => 'Без опыта работы',
            'type' => 'constant',
            'content' => '
            Требования
            начальное знание php, mysql, понимание ООП
            - опыт работы в команде
            - опыт работы с крупными объёмами данных (20+ Гб текстовой информации)
            Желательно
            опыт работы с Laravel
            опыт работы с curl
            Условия
             оформление по ТК РФ
             светлый, просторный офис
             чай, кофе, печеньки, пинг-понг, мягкие пуфы и кресло-мешки
             официальное трудоустройство, белая зарплата
            ',
            'published' => true,
            'published_at' => new Carbon('now'),
            'department_id' => 2
        ]);
        Employee::create([
            'name' => 'Александр',
            'last_name' => 'Пригода',
            'post' => 'Руководитель компании Albus',
            'department_id' => 1,
            'email' => 'prigoda.a@albus-it.ru',
            'phone' => '+7(989)5-1234-44'
        ]);
    }

}
