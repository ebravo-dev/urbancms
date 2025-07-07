<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Property;
use App\Models\ArticleImage;
use App\Traits\HandlesImageProcessing;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ConvertImagesToWebP extends Command
{
    use HandlesImageProcessing;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:convert-to-webp {--dry-run : Show what would be converted without actually converting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert existing images to WebP format for better performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - No files will be modified');
            $this->line('');
        }

        $this->info('🖼️  Starting image conversion to WebP...');
        $this->line('');

        // Convert property images
        $this->convertPropertyImages($dryRun);
        
        // Convert article images
        $this->convertArticleImages($dryRun);

        $this->line('');
        $this->info('✅ Image conversion completed!');
    }

    private function convertPropertyImages($dryRun)
    {
        $this->info('📋 Processing Property Images...');
        
        $properties = Property::whereNotNull('image1')
            ->orWhereNotNull('image2')
            ->orWhereNotNull('image3')
            ->orWhereNotNull('image4')
            ->get();

        $convertedCount = 0;
        $skippedCount = 0;

        foreach ($properties as $property) {
            foreach (['image1', 'image2', 'image3', 'image4'] as $field) {
                if ($property->$field && !str_ends_with($property->$field, '.webp')) {
                    if (Storage::disk('public')->exists($property->$field)) {
                        $this->line("   Converting: {$property->$field}");
                        
                        if (!$dryRun) {
                            try {
                                // Convert to WebP using existing image method
                                $newPath = $this->convertExistingImageToWebP(
                                    $property->$field,
                                    'property-images',
                                    [
                                        'max_width' => config('image.property_max_width', 1200),
                                        'max_height' => config('image.property_max_height', 800),
                                        'quality' => config('image.quality', 85),
                                    ]
                                );
                                
                                // Update database
                                $oldPath = $property->$field;
                                $property->$field = $newPath;
                                $property->save();
                                
                                // Delete old file
                                Storage::disk('public')->delete($oldPath);
                                
                                $convertedCount++;
                                $this->line("   ✅ Converted to: {$newPath}");
                                
                            } catch (\Exception $e) {
                                $this->error("   ❌ Failed to convert: {$property->$field} - {$e->getMessage()}");
                            }
                        } else {
                            $convertedCount++;
                        }
                    } else {
                        $this->warn("   ⚠️  File not found: {$property->$field}");
                        $skippedCount++;
                    }
                } else {
                    $skippedCount++;
                }
            }
        }

        $this->line("   📊 Properties: {$convertedCount} converted, {$skippedCount} skipped");
    }

    private function convertArticleImages($dryRun)
    {
        $this->info('📰 Processing Article Images...');
        
        $articleImages = ArticleImage::whereNotLike('image_path', '%.webp')->get();
        
        $convertedCount = 0;
        $skippedCount = 0;

        foreach ($articleImages as $articleImage) {
            if (Storage::disk('public')->exists($articleImage->image_path)) {
                $this->line("   Converting: {$articleImage->image_path}");
                
                if (!$dryRun) {
                    try {
                        // Convert to WebP using existing image method
                        $newPath = $this->convertExistingImageToWebP(
                            $articleImage->image_path,
                            'articles',
                            [
                                'max_width' => config('image.article_max_width', 1200),
                                'max_height' => config('image.article_max_height', 800),
                                'quality' => config('image.quality', 85),
                            ]
                        );
                        
                        // Update database
                        $oldPath = $articleImage->image_path;
                        $articleImage->image_path = $newPath;
                        $articleImage->save();
                        
                        // Delete old file
                        Storage::disk('public')->delete($oldPath);
                        
                        $convertedCount++;
                        $this->line("   ✅ Converted to: {$newPath}");
                        
                    } catch (\Exception $e) {
                        $this->error("   ❌ Failed to convert: {$articleImage->image_path} - {$e->getMessage()}");
                    }
                } else {
                    $convertedCount++;
                }
            } else {
                $this->warn("   ⚠️  File not found: {$articleImage->image_path}");
                $skippedCount++;
            }
        }

        $this->line("   📊 Articles: {$convertedCount} converted, {$skippedCount} skipped");
    }
}
