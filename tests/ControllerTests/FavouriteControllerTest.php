<?php


class FavouriteControllerTest extends BaseTest
{
    public function testUserCanFetchFavouriteSongs(): void
    {
        $this->get('/favourite', $this->headers);
        $this->assertResponseOk();
    }
}
