<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'is_for_sale' => $this->is_for_sale,
            'type' => $this->is_for_sale ? 'Venta' : 'Renta',
            'location' => [
                'line1' => $this->location_line1,
                'line2' => $this->location_line2,
                'line3' => $this->location_line3,
            ],
            'google_maps_url' => $this->google_maps_url,
            'features' => array_filter([
                $this->feature1,
                $this->feature2,
                $this->feature3,
                $this->feature4,
                $this->feature5,
                $this->feature6,
                $this->feature7,
                $this->feature8,
            ]),
            'investment' => $this->investment,
            'investment_formatted' => '$ ' . number_format($this->investment, 2) . ' MXN',
            'images' => array_filter([
                $this->image1 ? $this->isExternalUrl($this->image1) ? $this->image1 : asset('storage/' . $this->image1) : null,
                $this->image2 ? $this->isExternalUrl($this->image2) ? $this->image2 : asset('storage/' . $this->image2) : null,
                $this->image3 ? $this->isExternalUrl($this->image3) ? $this->image3 : asset('storage/' . $this->image3) : null,
                $this->image4 ? $this->isExternalUrl($this->image4) ? $this->image4 : asset('storage/' . $this->image4) : null,
            ]),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Check if a given string is an external URL.
     *
     * @param string $url
     * @return bool
     */
    private function isExternalUrl(string $url): bool
    {
        return str_starts_with($url, 'http://') || str_starts_with($url, 'https://');
    }
}
