<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace Tests\Database;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class TagTest
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class TagTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 测试Tag
     */
    public function testTag()
    {
        $tag = Tag::factory()->create();
        $this->assertDatabaseHas('tags', [
            'id' => $tag->id,
        ]);
    }
}
