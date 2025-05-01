<?php

namespace Modules\YrpDashboard\Admin\Resources;

use Modules\YrpDashboard\Admin\Resources\UserResource\Pages;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Modules\YrpDashboard\Entities\YrpUser;

class UserResource extends Resource
{
    protected static ?string $model = YrpUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $slug = 'user';

    protected static ?string $modelLabel = 'ผู้ใช้งาน';
    protected static ?string $navigationLabel = 'ผู้ใช้งาน';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'การจัดการผู้ใช้';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'first_name', 'last_name', 'email', 'position', 'department'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'ชื่อผู้ใช้งาน' => $record->name,
            'ชื่อ-นามสกุล' => $record->prefix_code . ' ' . $record->first_name . ' ' . $record->last_name,
            'ตำแหน่ง' => $record->position,
            'หน่วยงาน' => $record->department,
            'Email' => $record->email,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('ข้อมูลบัญชีผู้ใช้')
                    ->description('กรุณากรอกข้อมูลบัญชีหลักสำหรับเข้าใช้งานระบบ')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('ชื่อผู้ใช้')
                            ->placeholder('ระบุชื่อผู้ใช้งาน')
                            ->unique(static::getModel(), 'name', ignoreRecord: true)
                            ->required()
                            ->maxLength(191),

                        Forms\Components\TextInput::make('email')
                            ->label('อีเมล')
                            ->placeholder('example@email.com')
                            ->helperText('อีเมลที่ติดต่อได้')
                            ->email()
                            ->required()
                            ->unique(static::getModel(), 'email', ignoreRecord: true)
                            ->maxLength(191),

                        Forms\Components\TextInput::make('password')
                            ->label('รหัสผ่าน')
                            ->password()
                            ->dehydrateStateUsing(fn($state) => !empty($state) ? Hash::make($state) : null)
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create')
                            ->autocomplete('new-password')
                            ->minLength(6)
                            ->helperText(fn(string $context): string =>
                                $context === 'edit'
                                ? 'ปล่อยว่างหากไม่ต้องการเปลี่ยนรหัสผ่าน'
                                : 'แนะนำใส่รหัสผ่านให้มีความซับซ้อน มากกว่า 6 ตัวอักษร'),

                        Forms\Components\Select::make('role')
                            ->label('สิทธิ์การใช้งาน')
                            ->options([
                                'admin' => 'ผู้ดูแลระบบ',
                                'department_staff' => 'เจ้าหน้าที่แผนก',
                            ])
                            ->helperText('กำหนดสิทธิ์การเข้าถึงระบบ')
                            ->required()
                            ->default('department_staff'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Forms\Components\Section::make('ข้อมูลส่วนตัว')
                    ->description('กรอกข้อมูลส่วนตัวของผู้ใช้งาน')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('prefix_code')
                                    ->label('คำนำหน้าชื่อ')
                                    ->options([
                                        'นาย' => 'นาย',
                                        'นาง' => 'นาง',
                                        'นางสาว' => 'นางสาว',
                                    ])
                                    ->searchable()
                                    ->required(),

                                Forms\Components\TextInput::make('first_name')
                                    ->label('ชื่อ')
                                    ->placeholder('ระบุชื่อจริง')
                                    ->required()
                                    ->maxLength(191),

                                Forms\Components\TextInput::make('last_name')
                                    ->label('นามสกุล')
                                    ->placeholder('ระบุนามสกุล')
                                    ->required()
                                    ->maxLength(191),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('position')
                                    ->label('ตำแหน่ง')
                                    ->placeholder('ระบุตำแหน่งงาน')
                                    ->helperText('ระบุตำแหน่งงานของผู้ใช้งาน')
                                    ->maxLength(191),

                                Forms\Components\Select::make('department')
                                    ->label('หน่วยงาน/แผนก')
                                    ->placeholder('-- เลือกหน่วยงาน --')
                                    ->options(function () {
                                        return YrpUser::distinct()
                                            ->whereNotNull('department')
                                            ->where('department', '!=', '')
                                            ->orderBy('department')
                                            ->pluck('department', 'department')
                                            ->toArray();
                                    })
                                    ->searchable() // เพิ่มช่องค้นหาที่จะกรองตัวเลือกตามที่พิมพ์
                                    ->allowHtml() // อนุญาตให้เพิ่ม HTML ในตัวเลือก
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label('ชื่อหน่วยงาน/แผนก')
                                            ->required()
                                            ->maxLength(191),
                                    ])
                                    ->createOptionUsing(function (array $data) {
                                        return $data['name'];
                                    }),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('ชื่อผู้ใช้งาน')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('fullname')
                    ->label('ชื่อ-นามสกุล')
                    ->formatStateUsing(fn(YrpUser $record): string =>
                        $record->prefix_code . ' ' . $record->first_name . ' ' . $record->last_name)
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(['first_name']),

                Tables\Columns\TextColumn::make('position')
                    ->label('ตำแหน่ง')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('department')
                    ->label('หน่วยงาน')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('อีเมล')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('role')
                    ->label('สิทธิ์การใช้งาน')
                    ->enum([
                        'admin' => 'ผู้ดูแลระบบ',
                        'department_staff' => 'หน่วยงานเจ้าหน้าที่',
                    ])
                    ->colors([
                        'danger' => 'admin',
                        'primary' => 'department_staff',
                    ])
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('สิทธิ์การใช้งาน')
                    ->options([
                        'admin' => 'ผู้ดูแลระบบ',
                        'department_staff' => 'หน่วยงานเจ้าหน้าที่',
                    ]),

                Tables\Filters\SelectFilter::make('department')
                    ->label('หน่วยงาน/แผนก')
                    ->options(function () {
                        // สร้าง unique list ของหน่วยงานทั้งหมดในระบบ
                        return YrpUser::distinct()
                            ->whereNotNull('department')
                            ->pluck('department', 'department')
                            ->toArray();
                    })
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public static function canDeleteAny(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public static function canView(Model $record): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }
}
